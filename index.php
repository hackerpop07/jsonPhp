<?php
function loadRegistrations($filename)
{
    $jsondata = file_get_contents($filename);
    $arr_data = json_decode($jsondata, true);
    return $arr_data;
}

function saveDataJSON($filename, $name, $email, $phone)
{
    try {
        $contact = ['name' => $name,
            'email' => $email,
            'phone' => $phone];
        $arr_data = loadRegistrations($filename);
        array_push($arr_data, $contact);
        $jsondata = json_encode($arr_data, JSON_PRETTY_PRINT);
        file_put_contents($filename, $jsondata);
        echo "Lưu dữ liệu thành công!";
    } catch (Exception $e) {
        echo "Lỗi: ", $e->getMessage(), "\n";
    }
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = $_POST["name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $has_error = true;

    if (empty($name) == 1) {
        $nameError = "Name không được bỏ trống!";
        $has_error = false;
    };
    if (empty($email)) {
        $emailError = "Email không được để trống!";
        $has_error = false;
    } else {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailError = "Định dạng email sai (xxx@xxx.xxx.xxx)!";
            $has_error = false;
        }
    }
    if (empty($phone) == 1) {
        $phoneError = "Phone không được bỏ trống!";
        $has_error = false;
    };
    if ($has_error) {
        saveDataJSON("package.json", $name, $email, $phone);
        $name = NULL;
        $email = NULL;
        $phone = NULL;
    }

}

?>
<!doctype html>
<html lang="vn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<form method="post" class="profile">
    <input id="name" name="name" placeholder="Tên người dùng">
    <span class="error">*<?php echo $nameError; ?></span>
    <br>
    <input id="email" name="email" placeholder="Email">
    <span class="error">*<?php echo $emailError; ?></span>
    <br>
    <input id="phone" type="number" name="phone" placeholder="Điện thoại">
    <span class="error">*<?php echo $phoneError; ?></span>
    <br>
    <input id="submit" type="submit" value="send">
    <br>
    <h2 id="tieude">Danh sách</h2>
    <table>
        <tr>
            <td>STT</td>
            <td>Name</td>
            <td>Email</td>
            <td>Phone</td>
        </tr>
        <?php function display($filename)
        {

            $jsondata = file_get_contents($filename);
            $arr_data = json_decode($jsondata, true);
            foreach ($arr_data as $index => $value):
                ?>
                <tr>
                    <td><?php echo $index + 1; ?></td>
                    <td><?php echo $value["name"] ?></td>
                    <td><?php echo $value["email"] ?></td>
                    <td><?php echo $value["phone"] ?></td>
                </tr>
            <?php endforeach;
        }

        display("package.json");
        ?>
    </table>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        #tieude {
        text-align: center;
        }

        #name, #email, #phone {
            width: 200px;
            border: 2px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
            padding: 12px 10px 12px 10px;
        }

        #submit {
            border-radius: 2px;
            padding: 10px 32px;
            font-size: 16px;
        }

        .profile {
            height: auto;
            width: 1000px;
            overflow: hidden;
        }
        .error {
            color: #FF0000;
        }

        .profile img {
            width: 100%;
        }
    </style>
</form>

</body>
</html>
