<?php
// ��� ���� �� ����� ��������
include 'db_connect.php';

// ����� �� ������ �� ����� �� ���� POST (���� �� ������ �� ������)
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // ���� ��� ����� �������� ������ �� ������
    if (isset($_POST['admin_name']) && isset($_POST['email'])) {

        // ���� �������� ����� �� ���� ������ �� ��� SQL
        $admin_name = mysqli_real_escape_string($conn, $_POST['admin_name']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);

        // ���� ������� SQL ����� ���� ������ �� ����� ��������
        $sql = "INSERT INTO admin (Admin_Name, Email) VALUES ('$admin_name', '$email')";

        // ���� ��������� ����� ��� ��� �� ��
        if (mysqli_query($conn, $sql)) {
            // ��� ����� ����͡ ���� ����� ���� ��� ������ ����� ������
            echo "<div style='background-color: #d4edda; color: #155724; padding: 10px; border: 1px solid #c3e6cb; border-radius: 4px; text-align: center;'>Admin added successfully.</div><br>";
            echo "<div style='text-align: center;'><a href='data1.php' style='background-color: #007bff; color: white; border: none; padding: 10px 15px; border-radius: 4px; cursor: pointer; text-decoration: none;'>Back to Admin Panel</a></div>";
        } else {
            // ��� ��� ��� ��� ����� ������� ���� ����� ���� �� �������
            echo "<div style='background-color: #f8d7da; color: #721c24; padding: 10px; border: 1px solid #f5c6cb; border-radius: 4px; text-align: center;'>Error adding admin: " . mysqli_error($conn) . "</div><br>";
            echo "<div style='text-align: center;'><a href='add_admin.php' style='background-color: #6c757d; color: white; border: none; padding: 10px 15px; border-radius: 4px; cursor: pointer; text-decoration: none;'>Try Again</a></div>";
        }
    } else {
        // ��� ������ �� ���� �������� �������� (����� �� ������� ����)
        echo "<div style='background-color: #f8d7da; color: #721c24; padding: 10px; border: 1px solid #f5c6cb; border-radius: 4px; text-align: center;'>Error: Admin Name and Email are required.</div><br>";
        echo "<div style='text-align: center;'><a href='add_admin.php' style='background-color: #6c757d; color: white; border: none; padding: 10px 15px; border-radius: 4px; cursor: pointer; text-decoration: none;'>Go Back</a></div>";
    }

    // ���� ������� �� ����� ��������
    mysqli_close($conn);

} else {
    // ��� ��� ���� ���� ������ ���� �� ���� ������ (���� �� �� ���� ������)
    echo "<div style='background-color: #f8d7da; color: #721c24; padding: 10px; border: 1px solid #f5c6cb; border-radius: 4px; text-align: center;'>This page cannot be accessed directly.</div><br>";
    echo "<div style='text-align: center;'><a href='data1.php' style='background-color: #007bff; color: white; border: none; padding: 10px 15px; border-radius: 4px; cursor: pointer; text-decoration: none;'>Back to Admin Panel</a></div>";
}
?>
