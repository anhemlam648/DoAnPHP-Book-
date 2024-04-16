<?php
require_once('C:/xampp/php/www/webdoctruyen/config/database.php');

class StoryModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAllStories() {
        $query = "SELECT * FROM stories";
        $result = $this->db->conn->query($query);

        if ($result->num_rows > 0) {
            $storiesList = array();
            while ($row = $result->fetch_assoc()) {
                $storiesList[] = $row;
            }
            return $storiesList;
        } else {
            return array(); 
        }
    }

    public function getStoryById($storyId) {
        $query = "SELECT * FROM stories WHERE id = $storyId";
        $result = $this->db->conn->query($query);

        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return null; // Trả về null nếu không tìm thấy truyện
        }
    }

    //tìm kiếm
    public function searchName($name)
    {
        $query = "SELECT * FROM stories WHERE title LIKE ?";
        $name = '%' . $name . '%';

        $stmt = $this->db->conn->prepare($query);
        $stmt->bind_param("s", $name);
        $stmt->execute();

        $result = $stmt->get_result();
        $stories = [];

        while ($row = $result->fetch_assoc()) {
            $stories[] = $row;
        }

        return $stories;
    }
    //thêm sách
    // public function addStory($title, $description, $image) {
    //     $query = "INSERT INTO stories (title, description, image) 
    //               VALUES ('$title', '$description', '$image')";

    //     return $this->db->conn->query($query);
    // }
//     public function addStory($title, $description, $image, $author) {
//     $query = "INSERT INTO stories (title, description, image, author) 
//               VALUES ('$title', '$description', '$image', '$author')";

//     return $this->db->conn->query($query);
// }
 public function addStory($title, $description, $image, $author, $typeId, $content) {
    $query = "INSERT INTO stories (title, description, image, author, type_id, content) 
              VALUES ('$title', '$description', '$image', '$author', '$typeId','$content')";
    return $this->db->conn->query($query);
}

public function updateStory($id, $title, $description, $image, $author, $typeId, $content) {
    $query = "UPDATE stories SET 
              title = '$title', 
              description = '$description', 
              image = '$image', 
              author = '$author', 
              type_id = '$typeId', 
              content = '$content' 
              WHERE id = $id";

    return $this->db->conn->query($query);
}

public function deleteStory($id) {
    $query = "DELETE FROM stories WHERE id = $id";
    return $this->db->conn->query($query);
}
//     //lấy dữ liệu
//   // Example for getAllAuthors function
//         public function getAllAuthors() {
//             $query = "SELECT id, name as author_name FROM users";
//             $result = $this->db->conn->query($query);

//             if ($result->num_rows > 0) {
//                 $authorsList = array();
//                 while ($row = $result->fetch_assoc()) {
//                     $authorsList[] = $row;
//                 }
//                 return $authorsList;
//             } else {
//                 return array();
//             }
//         }
//         //lấy dữ liệu
//         public function getAllTypes() {
//             $query = "SELECT id, name as type_name FROM storiestype";
//             $result = $this->db->conn->query($query);

//             if ($result === false) {
//                 // Handle query error
//                 die("Query failed: " . $this->db->conn->error);
//             }

//             $typesList = $result->fetch_all(MYSQLI_ASSOC);
//             return $typesList;
//         }

//         //lấy dữ liệu
//         public function getAllContents() {
//             $query = "SELECT id, content as content_name FROM content";
//             $result = $this->db->conn->query($query);

//             if ($result === false) {
//                 // Handle query error
//                 die("Query failed: " . $this->db->conn->error);
//             }

//             $contentsList = $result->fetch_all(MYSQLI_ASSOC);
//             return $contentsList;
//         }
            //Phân trang truyện
            public function getStoriesPerPage($offset, $limit)
            {
                $query = "SELECT * FROM stories LIMIT $offset, $limit";
                $result = $this->db->conn->query($query);

                if ($result === false) {
                    // Xử lý lỗi truy vấn
                    die("Query failed: " . $this->db->conn->error);
                }

                $stories = [];
                while ($row = $result->fetch_assoc()) {
                    $stories[] = $row;
                }

                return $stories;
            }

        //Tổng số truyện
        public function getTotalStories() {
            $query = "SELECT COUNT(*) as total FROM stories";
            $result = $this->db->conn->query($query);
            $row = $result->fetch_assoc();
            return $row['total'];
        }
}

// Sử dụng
$storyModel = new StoryModel();
$allStories = $storyModel->getAllStories();
// In ra để kiểm tra
// print_r($allStories);
?>