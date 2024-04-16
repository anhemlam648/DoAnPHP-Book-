<?php
require_once('C:/xampp/php/www/webdoctruyen/config/database.php');

class RoleModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Lấy tất cả các roles
    public function getAllRoles() {
        $query = "SELECT * FROM roles";
        $result = $this->db->conn->query($query);

        if ($result === false) {
            // Xử lý lỗi truy vấn
            die("Query failed: " . $this->db->conn->error);
        }

        $rolesList = $result->fetch_all(MYSQLI_ASSOC);
        return $rolesList;
    }

    // Lấy thông tin role dựa trên ID
    public function getRoleById($roleId) {
        $query = "SELECT * FROM roles WHERE id = $roleId";
        $result = $this->db->conn->query($query);

        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return null; // Trả về null nếu không tìm thấy role
        }
    }

    // Lấy thông tin role dựa trên tên
    public function getRoleByName($roleName) {
        $query = "SELECT * FROM roles WHERE name = '$roleName'";
        $result = $this->db->conn->query($query);

        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return null; // Trả về null nếu không tìm thấy role
        }
    }

    // Thêm mới một role
    public function addRole($name) {
        $query = "INSERT INTO roles (name) VALUES ('$name')";
        return $this->db->conn->query($query);
    }
//   // Lấy các roles có role_id = 1 cho một user cụ thể
//      public function getRolesWithRoleIdOne()
//     {
//         $query = "SELECT role_id FROM user_roles WHERE role_id = 1 ";
//         $result = $this->db->conn->query($query);

//         if ($result === false) {
//             // Xử lý lỗi truy vấn
//             die("Query failed: " . $this->db->conn->error);
//         }

//         $rolesList = $result->fetch_all(MYSQLI_ASSOC);
//         return $rolesList;
//     }
        // public function getRolesWithRoleIdOne()
        // {
        //     $query = "SELECT * FROM user_roles WHERE role_id = 1 AND role_id != 2 LIMIT 1";
        //     $result = $this->db->conn->query($query);

        //     if ($result === false) {
        //         // Xử lý lỗi truy vấn
        //         die("Query failed: " . $this->db->conn->error);
        //     }

        //     $user = $result->fetch_assoc(); 
        //     return $user;
        // }
        public function getRolesWithRoleIdOne()
        {
            session_start();
            // Lấy id của người dùng từ phiên hoặc nguồn dữ liệu khác
            $userId = $_SESSION['user_id']; // Ví dụ: Lấy từ phiên
            // Hoặc: $userId = $otherDataSource->getUserId();
        
            $query = "SELECT * FROM user_roles 
                      LEFT JOIN roles ON user_roles.role_id = roles.role_id 
                      WHERE user_id = $userId AND roles.role_name = 'ADMIN'";
            $result = $this->db->conn->query($query);
        
            if ($result === false) {
                // Xử lý lỗi truy vấn
                die("Query failed: " . $this->db->conn->error);
            }
        
            $rolesList = $result->fetch_all(MYSQLI_ASSOC);
            return $rolesList;
        }
}
// Sử dụng
$roleModel = new RoleModel();
$allRoles = $roleModel->getRolesWithRoleIdOne();
?>
