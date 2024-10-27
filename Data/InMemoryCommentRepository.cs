using System.Collections.Generic;
using System.Threading.Tasks;
using CommentModerationAPI.Models;
using CommentModerationAPI.Interfaces;

namespace CommentModerationAPI.Data
{
    public class InMemoryCommentRepository 
    {
        private List<Comment> _comments = new List<Comment>();

        public async Task<List<Comment>> GetCommentsAsync()
        {
            return await Task.FromResult(_comments);
        }

        public async Task<Comment> AddCommentAsync(Comment comment)
        {
            _comments.Add(comment);
            return await Task.FromResult(comment);
        }
    }
}
