namespace CommentModerationAPI.Models
{
    public class CommentDetails
    {
        public string Id { get; set; }
        public string Message { get; set; }
        public string place_id { get; set; }
        public string moderation { get; set; }
        public string reason { get; set; }
    }
}
