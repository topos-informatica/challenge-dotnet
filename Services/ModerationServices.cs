// Services/ModerationService.cs
using System;
using System.Net.Http;
using System.Text;
using System.Threading.Tasks;
using Newtonsoft.Json;
using System.Collections.Generic;
using CommentModerationAPI.Models;
using Microsoft.Extensions.Configuration;
using Microsoft.Extensions.Logging;

namespace CommentModerationAPI.Services
{
    public class ModerationService
    {
        private readonly HttpClient _httpClient;
        private readonly string _openAiApiKey;
        private readonly ILogger<ModerationService> _logger;

        private const string OpenAiApiUrl = "https://api.openai.com/v1/chat/completions";
        private const string Model = "gpt-3.5-turbo";

        public ModerationService(HttpClient httpClient, IConfiguration configuration, ILogger<ModerationService> logger)
        {
            httpClient.Timeout = TimeSpan.FromMinutes(5);
            _httpClient = httpClient;
            _openAiApiKey = configuration["OpenAI:ApiKey"];
            _logger = logger;
        }

        public async Task<List<Place>> GetPlacesAsync()
        {
            var response = await _httpClient.GetAsync("https://go-tour-bahia.onrender.com/place");

            if (response.IsSuccessStatusCode)
            {
                var jsonResponse = await response.Content.ReadAsStringAsync();
                var places = JsonConvert.DeserializeObject<List<Place>>(jsonResponse);

                if (places != null)
                {
                    foreach (var place in places)
                    {
                        foreach (var comment in place.Comments)
                        {
                            comment.moderation = await ModerateCommentAsync(comment.Message);
                            comment.reason = await ClassifyCommentAsync(comment.Message);

                            if (IsCommentReportable(comment.reason))
                            {
                                await ReportCommentAsync(comment.Id, place.Id, comment.Message, comment.reason);
                            }
                        }
                    }
                }
                return places;
            }

            _logger.LogError("Falha ao buscar dados da API externa. Código de status: {StatusCode}", response.StatusCode);
            throw new Exception($"Falha ao buscar dados da API externa: {response.StatusCode}");
        }

        private async Task<string> ModerateCommentAsync(string message)
        {
            using var request = new HttpRequestMessage(HttpMethod.Post, OpenAiApiUrl);
            request.Headers.Add("Authorization", $"Bearer {_openAiApiKey}");

            var requestBody = new
            {
                model = Model,
                messages = new[]
                {
                    new { role = "system", content = "You are a comment moderator. Please review the following comment and provide a brief, descriptive assessment of its appropriateness if it is a biased/inappropriate/or low-value comment." },
                    new { role = "user", content = message }
                },
                temperature = 0.7
            };

            request.Content = new StringContent(JsonConvert.SerializeObject(requestBody), Encoding.UTF8, "application/json");

            var response = await _httpClient.SendAsync(request);

            if (response.IsSuccessStatusCode)
            {
                var jsonResponse = await response.Content.ReadAsStringAsync();
                dynamic result = JsonConvert.DeserializeObject(jsonResponse);

                if (result?.choices != null && result.choices.Count > 0)
                {
                    return result.choices[0].message.content.ToString();
                }
            }

            return "Nenhum resultado de moderação.";
        }

        private async Task<string> ClassifyCommentAsync(string message)
        {
            using var request = new HttpRequestMessage(HttpMethod.Post, OpenAiApiUrl);
            request.Headers.Add("Authorization", $"Bearer {_openAiApiKey}");

            var requestBody = new
            {
                model = Model,
                messages = new[]
                {
                    new { role = "system", content = "Rate the following comment by choosing ONLY one of the following options: SPAM, HATE, FAKE, BULLYING, VIOLENCE, SCAM, SUICIDE, APPROPRIATE." },
                    new { role = "user", content = message }
                },
                temperature = 0.7
            };

            request.Content = new StringContent(JsonConvert.SerializeObject(requestBody), Encoding.UTF8, "application/json");

            var response = await _httpClient.SendAsync(request);

            if (response.IsSuccessStatusCode)
            {
                var jsonResponse = await response.Content.ReadAsStringAsync();
                dynamic result = JsonConvert.DeserializeObject(jsonResponse);

                if (result?.choices != null && result.choices.Count > 0)
                {
                    return result.choices[0].message.content.ToString();
                }
            }

            return "APPROPRIATE"; 
        }

        // Função para verificar se o comentário deve ser reportado
        private bool IsCommentReportable(string reason)
        {
            return reason.Equals("SPAM", StringComparison.OrdinalIgnoreCase) ||
                   reason.Equals("HATE", StringComparison.OrdinalIgnoreCase) ||
                   reason.Equals("FAKE", StringComparison.OrdinalIgnoreCase) ||
                   reason.Equals("BULLYING", StringComparison.OrdinalIgnoreCase) ||
                   reason.Equals("VIOLENCE", StringComparison.OrdinalIgnoreCase) ||
                   reason.Equals("SCAM", StringComparison.OrdinalIgnoreCase) ||
                   reason.Equals("SUICIDE", StringComparison.OrdinalIgnoreCase);
        }

        // Função para reportar o comentário
        private async Task ReportCommentAsync(string commentId, string placeId, string message, string reason)
        {
            var reportBody = new
            {
                commentId = commentId,
                placeId = placeId,
                message = message,
                reason = reason
            };

            using var request = new HttpRequestMessage(HttpMethod.Post, "https://go-tour-bahia.onrender.com/report");
            request.Content = new StringContent(JsonConvert.SerializeObject(reportBody), Encoding.UTF8, "application/json");

            var response = await _httpClient.SendAsync(request);

            if (response.IsSuccessStatusCode)
            {
                // Log para confirmar que o comentário foi reportado
                _logger.LogInformation("Comment with ID {CommentId} reported successfully for reason: {Reason}", commentId, reason);
            }
            else
            {
                var errorResponse = await response.Content.ReadAsStringAsync();
                _logger.LogError("Failed to report comment. Status code: {StatusCode}, Response: {Response}", response.StatusCode, errorResponse);
                throw new Exception("Failed to report comment");
            }

            // Logando todos os campos do corpo da requisição de report
            _logger.LogInformation($"Sending report for comment ID: {commentId}, Place ID: {placeId}, Message: {message}, Reason: {reason}");
        }
    }
}
