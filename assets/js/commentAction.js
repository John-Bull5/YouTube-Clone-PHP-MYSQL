function postComment(button, postedBy, videoId, replyTo, containerClass) {
    // fetch the textarea

    let textarea = $(button).siblings('textarea');

    let commentText = textarea.val();

    textarea.val('');

    // check if it is an empty array

    if (commentText) {
        $.post('ajax/postComment.php', {
            commentText: commentText,
            postedBy: postedBy,
            videoId: videoId,
            responseTo: replyTo
        })
            .done(function (comment) {
                $('.' + containerClass).prepend(comment);
            });
    } else {
        console.log('comment is empty');
    }
}

function likeComment(commentId,button,videoId) {
    $.post("ajax/likeComment.php",{commentId:commentId,videoId:videoId})
        .done((data) => {
            let likeButton = $(button);
            let dislikeButton = $(button).siblings('.dislikeBtn');

            likeButton.addClass(".active")
            dislikeButton.removeClass('.active');
            let result = JSON.parse(data);
            //updateLikeValue(likeButton.find('.text'), result.likes);
            //updateLikeValue(dislikeButton.find('.text'), result.dislikes);
            
            if (result.likes < 0) {
                likeButton.removeClass('.active');
                likeButton.find('i:first').attr('class', 'fa fa-thumbs-up');
            } else {
                likeButton.find('i:first').attr('class', 'fa fa-thumbs-up thumbs_active');
            }
            dislikeButton.find('i:first').attr('class', 'fa fa-thumbs-down');
    });
}

function dislikeComment(commentId,button,videoId) {
    $.post("ajax/dislikeComment.php", {
        commentId:commentId,
        videoId: videoId
    })
        .done((data) => {
            let dislikeButton = $(button);
            let likeButton = $(button).siblings('.likeBtn');

            dislikeButton.addClass(".active")
            likeButton.removeClass('.active');
            let result = JSON.parse(data);
            //updateLikeValue(dislikeButton.find('.text'), result.dislikes);
            //updateLikeValue(likeButton.find('.text'), result.likes);
            
            if (result.dislikes < 0) {
                dislikeButton.removeClass('.active');
                dislikeButton.find('i:first').attr('class', 'fa fa-thumbs-down');
            } else {
                dislikeButton.find('i:first').attr('class', 'fa fa-thumbs-down thumbs_active');
            }
            likeButton.find('i:first').attr('class', 'fa fa-thumbs-up');
    });
}
