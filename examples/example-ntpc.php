<?php
	require 'NtpcOpenId.php';
	try {
		$openid = new NtpcOpenId('ntpc-openid.local');
		if ($_SERVER['REQUEST_METHOD'] == 'POST' && !$openid->mode) {
			$openid->identity = "https://openid.ntpc.edu.tw";
			header('Location: ' . $openid->authUrl());
		}
	} catch (ErrorException $e) {
		echo $e->getMessage();
	}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
</head>

<body>
    <?php if ($openid->mode && $openid->validate()) { ?>
    <?php   $attr = $openid->getNtpcData(); ?>
    <p>
        <a
            class="btn btn-sm btn-danger"
            href="<?= $_SERVER['PHP_SELF'] ?>"
        >登出</a>
    </p>
    <p>
        <table class="bordered">
            <tr>
                <th>欄位</th>
                <th>內容</th>
            </tr>
            <tr>
                <td>帳號</td>
                <td>
                    <?php var_dump($attr); ?>
                    <br />
                    <?= $attr['account'] ?></td>
            </tr>

            <tr>
                <td>公務信箱</td>
                <td><?= $attr['email'] ?></td>
            </tr>
            <tr>
                <td>單位簡稱</td>
                <td><?= $attr['school_title'] ?></td>
            </tr>
            <tr>
                <td>年級</td>
                <td><?= $attr['grade'] ?></td>
            </tr>
            <tr>
                <td>班級</td>
                <td><?= $attr['class'] ?></td>
            </tr>
            <tr>
                <td>座號</td>
                <td><?= $attr['class_no'] ?></td>
            </tr>
            <tr>
                <td>
                    <?php print_r($attr['workplaces'])?>
                </td>
            </tr>
        </table>
    </p>
    <p>

    </p>
    <?php } else { ?>
    <form method="post">
        <input
            type="submit"
            class="btn btn-sm btn-success"
            value="登入"
        />
    </form>
    <?php } ?>
</body>

</html>