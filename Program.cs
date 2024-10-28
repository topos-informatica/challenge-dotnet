using CommentModerationAPI.Data;
using CommentModerationAPI.Interfaces;
using CommentModerationAPI.Services;
using CommentModerationAPI.Middleware;

var builder = WebApplication.CreateBuilder(args);

builder.Configuration.AddJsonFile("appsettings.json", optional: true, reloadOnChange: true);

builder.Services.AddControllers();

builder.Services.AddHttpClient<ModerationService>(client =>
{
    client.Timeout = TimeSpan.FromMinutes(2);
});

builder.Services.AddSingleton<InMemoryCommentRepository>();

var apiKey = builder.Configuration["OpenAI:ApiKey"];
if (string.IsNullOrEmpty(apiKey))
{
    throw new InvalidOperationException("API Key is not set in the configuration.");
}

var app = builder.Build();
app.UseMiddleware<ErrorHandlingMiddleware>();

app.UseAuthorization();

app.MapControllers();

app.Run();
