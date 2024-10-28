using CommentModerationAPI.Models;
using System.Threading.Tasks;

namespace CommentModerationAPI.Interfaces
{
    public interface IModerationService
    {
        Task<ModerationResult> ModerateCommentAsync(Comment comment);
    }
}
