function likeVideo(button,videoId) {
    $.post("ajax/likeVideo.php",{videoId:videoId})
        .done((data) => {
            let likeButton = $(button);
            let dislikeButton = $(button).siblings('.dislikeBtn');

            likeButton.addClass(".active")
            dislikeButton.removeClass('.active');
            let result = JSON.parse(data);
            updateLikeValue(likeButton.find('.text'), result.likes);
            updateLikeValue(dislikeButton.find('.text'), result.dislikes);
            
            if (result.likes < 0) {
                likeButton.removeClass('.active');
                likeButton.find('i:first').attr('class', 'fa fa-thumbs-up');
            } else {
                likeButton.find('i:first').attr('class', 'fa fa-thumbs-up thumbs_active');
            }
            dislikeButton.find('i:first').attr('class', 'fa fa-thumbs-down');
    });
}

function dislikeVideo(button,videoId) {
    $.post("ajax/dislikeVideo.php", {
        videoId: videoId
    })
        .done((data) => {
            let dislikeButton = $(button);
            let likeButton = $(button).siblings('.likeBtn');

            dislikeButton.addClass(".active")
            likeButton.removeClass('.active');
            let result = JSON.parse(data);
            updateLikeValue(dislikeButton.find('.text'), result.dislikes);
            updateLikeValue(likeButton.find('.text'), result.likes);
            
            if (result.dislikes < 0) {
                dislikeButton.removeClass('.active');
                dislikeButton.find('i:first').attr('class', 'fa fa-thumbs-down');
            } else {
                dislikeButton.find('i:first').attr('class', 'fa fa-thumbs-down thumbs_active');
            }
            likeButton.find('i:first').attr('class', 'fa fa-thumbs-up');
    });
}

function updateLikeValue(element,value) {
    let likeValueCount = element.text() || 0;

    element.text(parseInt(likeValueCount) + parseInt(value));
}