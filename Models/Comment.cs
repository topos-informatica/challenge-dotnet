using CommentModerationAPI.Controllers;

public class Comment
{
    public string Id { get; private set; }
    public string PlaceId { get; set; }
    public string Message { get; set; }

    public string Moderation { get; set; }

    public Comment(string userId, string placeId, string message, string moderation )
    {
        Id = Guid.NewGuid().ToString();
        PlaceId = placeId;
        Message = message;
        Moderation = moderation;
    }
}
namespace CommentModerationAPI.Models
{
    public class Place
    {
        public string Id { get; set; } 
        public string Name { get; set; }
        public List<CommentDetails> Comments { get; set; }
    }
}
namespace CommentModerationAPI.Models
{
    public class ModerationResult
    {
        public string CommentId { get; set; }
        public string Moderation { get; set; }
        public bool IsApproved { get; set; }
    }
}
