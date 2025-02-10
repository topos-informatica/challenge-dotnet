<?php

namespace App\Contracts;

interface CommentModerationInterface
{
    public function isCommentToxic($comment): bool;
}