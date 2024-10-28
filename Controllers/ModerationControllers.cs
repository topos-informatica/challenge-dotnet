using Microsoft.AspNetCore.Mvc;
using System.Threading.Tasks;
using CommentModerationAPI.Services;

namespace CommentModerationAPI.Controllers
{
    [Route("api/[controller]")]
    [ApiController]
    public class ModerationController : ControllerBase
    {
        private readonly ModerationService _moderationService;

        public ModerationController(ModerationService moderationService)
        {
            _moderationService = moderationService;
        }

        // Endpoint para buscar dados da API externa e moderar
        [HttpGet("moderate")]
        public async Task<IActionResult> Moderate()
        {
            // Chama o método do serviço para obter dados da API externa
            var places = await _moderationService.GetPlacesAsync();

            // Retorna os dados obtidos
            return Ok(places);
        }
    }
}
