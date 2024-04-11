<?php
require_once('C:/xampp/php/www/webdoctruyen/app/models/StoryModel.php');
require_once('C:/xampp/php/www/webdoctruyen/app/models/CommentModel.php');
require_once('C:/xampp/php/www/webdoctruyen/app/controllers/CommentController.php');
require_once('C:/xampp/php/www/webdoctruyen/app/models/UserModel.php');
class DefaultController
{
    public function home(){
        include_once '/xampp/php/www/webdoctruyen/app/views/user/index.php';
    }
    public function index()
    {
        $storyModel = new StoryModel();
        $allStories = $storyModel->getAllStories();
    
        echo '<h2>Danh sách truyện</h2>';
        echo '<div class="story-container">'; 
    
        foreach ($allStories as $story) {
            echo '<div class="story">';
            if (!empty($story['image'])) {
                $imageData = base64_encode(file_get_contents($story['image']));
                $src = 'data:' . mime_content_type($story['image']) . ';base64,' . $imageData;
    
                echo '<img src="' . $src . '" alt="' . $story['title'] . '">';
            }
            echo '<h3 class="story-title">' . $story['title'] . '</h3>';
            echo '<p class="story-author">Tác giả: ' . $story['author'] . '</p>';
            echo '<a class="read-more-link" href="/app/views/user/story.php?id=' . $story['id'] . '">Đọc thêm</a>';
            echo '</div>';
        }
    
        echo '</div>'; 
    }

    public function showStoryDetails($storyId)
    {
        $storyModel = new StoryModel();
        $storyDetails = $storyModel->getStoryById($storyId);

        echo '<h2 class="detail-heading">Chi tiết truyện</h2>';
        echo '<div class="story-details">';
        echo '<div class="story-info">';
        echo '<h3 class="story-title">Tên Truyện: ' . $storyDetails['title'] . '</h3>';
        echo '<p class="story-author">Tác giả: ' . $storyDetails['author'] . '</p>';
        echo '</div>';
        echo '<div class="story-image">';
        if (!empty($storyDetails['image'])) {
            $imageData = base64_encode(file_get_contents($storyDetails['image']));
            $src = 'data:' . mime_content_type($storyDetails['image']) . ';base64,' . $imageData;

            echo '<img src="' . $src . '" alt="' . $storyDetails['title'] . '">';
        }
        echo '</div>';
        echo '<div class="story-description">';
        echo '<p>Mô tả: ' . $storyDetails['description'] . '</p>';
        echo '<a class="read-more-link" href="/app/views/user/read.php?id=' . $storyDetails['id'] . '">Đọc truyện</a>';
        // hiển thị button trái tim// Display the average rating
        // echo '<div class="heart-button-row">';
        // for ($i = 1; $i <= 5; $i++) {
        //     echo '<div class="heart-button" onclick="toggleHeartColor(' . $storyId . ', ' . $i . ')">';
        //     echo '<img id="heartIcon_' . $storyId . '_' . $i . '" src="/public/png/black-heart-silhouette-7.png" alt="Heart" style="width: 25px; height: auto;">';
        //     echo '</div>';
        // }
        // echo '<div id="averageRating_' . $storyId . '">0</div>'; 
        // echo '</div>';        
        echo '</div>';
        // Thêm các chi tiết khác cần hiển thị
        echo '</div>';
        echo '</div>';
    }

    // public function showStoryRead($storyId)
    // {
    //     $storyModel = new StoryModel();
    //     $storyRead = $storyModel->getStoryById($storyId);

    //     echo '<h2 class="detail-heading">Đọc Truyện</h2>';
    //     echo '</div>';
    //     echo '<div class="story-image">';
    //     if (!empty($storyRead['image'])) {
    //         $imageData = base64_encode(file_get_contents($storyRead['image']));
    //         $src = 'data:' . mime_content_type($storyRead['image']) . ';base64,' . $imageData;

    //         echo '<img src="' . $src . '" alt="' . $storyRead['title'] . '">';
    //     }
    //     echo '</div>';
    //     echo '<div class="story-description">';
    //     echo '<p>Nội dung: ' . $storyRead['content'] . '</p>';
    //     // Thêm các chi tiết khác cần hiển thị
    //     echo '</div>';
    //     echo '</div>';
    // }
    public function showStoryRead($storyId)
    {
        $storyModel = new StoryModel();
        $storyRead = $storyModel->getStoryById($storyId);
    
        // Hiển thị phần đọc truyện
        echo '<div class="story-container">';
        echo '<h2 class="detail-heading">Đọc Truyện</h2>';
        echo '</div>';
        echo '<div class="story-image">';
        if (!empty($storyRead['image'])) {
            $imageData = base64_encode(file_get_contents($storyRead['image']));
            $src = 'data:' . mime_content_type($storyRead['image']) . ';base64,' . $imageData;
    
            echo '<img src="' . $src . '" alt="' . $storyRead['title'] . '">';
        }
        echo '</div>';
        echo '<div class="story-description">';
        echo '<p>Nội dung: ' . $storyRead['content'] . '</p>';
        echo '</div>';
    
        // Hiển thị phần bình luận
        echo '<div class="comment-section">';
        echo '<h2 class="comment-heading">Bình Luận</h2>';
        echo '<form class="comment-form" method="post" action="#">'; 
        echo '<input type="hidden" name="story_id" value="' . $storyId . '">';
        echo '<textarea name="comment_content" placeholder="Nhập bình luận của bạn"></textarea>';
        echo '<button type="submit">Gửi</button>';
        echo '</form>';
    
        // Hiển thị danh sách các bình luận
        $commentModel = new CommentModel();
        $comments = $commentModel->getCommentsByStoryId($storyId);
        if (!empty($comments)) {
            echo '<div class="comment-list">';
            foreach ($comments as $comment) {
                echo '<div class="comment">';
                // Lấy thông tin người dùng từ UserModel
                $userModel = new UserModel();
                $user = $userModel->getUserById($comment['user_id']);
                if ($user) {
                    // Hiển thị tên người dùng và nội dung bình luận
                    echo '<p><strong>' . $user['name'] . '</strong>: ' . $comment['content'] . '</p>';
                } else {
                    // Hiển thị nội dung bình luận nếu không tìm thấy thông tin người dùng
                    echo '<p>' . $comment['content'] . '</p>';
                }
                echo '</div>';
            }
            echo '</div>';
        } else {
            echo '<p class="no-comments">Chưa có bình luận nào.</p>';
        }        
        echo '</div>';
    }
    
}
?>
<!-- <script>
    function toggleHeartColor(storyId, rating) {
        var heartIcons = document.querySelectorAll('[id^="heartIcon_' + storyId + '"]');
        var averageRatingElement = document.getElementById("averageRating_" + storyId);
        for (var i = 1; i <= 5; i++) {
            var heartIcon = document.getElementById("heartIcon_" + storyId + "_" + i);
            if (i <= rating) {
                heartIcon.src = "/public/png/heart-image-transparent-22.png";
            } else {
                heartIcon.src = "/public/png/black-heart-silhouette-7.png";
            }
        }

        // Calculate the total rating
        var currentRating = parseFloat(localStorage.getItem("averageRating_" + storyId)) || 0;
        var totalCount = parseFloat(localStorage.getItem("totalCount_" + storyId)) || 0;
        var totalRating = currentRating * totalCount; // This is incorrect
        totalRating += rating; // Add the new rating to the total rating
        totalCount++; // Increment the total count

        // Calculate the average rating
        var averageRating = totalRating / totalCount;
        localStorage.setItem("averageRating_" + storyId, averageRating.toFixed(1));
        var averageRating = parseFloat(localStorage.getItem("averageRating_" + storyId)) || 0;
        // Display the average rating if it's not 0
        if (averageRating !== 0) {
            averageRatingElement.textContent = averageRating.toFixed(1);
        }
    }
</script> -->
