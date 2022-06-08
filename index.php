<?php

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Mpdf\Mpdf;

ob_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>SREDA test certificate generator</title>
    <link rel="stylesheet" href="certificate.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css"
          rel="stylesheet"
          integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor"
          crossorigin="anonymous">
    <?php
    if (isset($_POST['submit'])) {
        $certificate_number = $_POST['certificate_number'];
        $course_name = $_POST['course_name'];
        $student_name = $_POST['student_name'];
        $end_date = $_POST['end_date'];

        if (!empty($end_date) && $end_date < date("Y-m-d")) {
            $error = 'Please enter a valid date!';
            echo $error;
        }
        require_once __DIR__ . '/vendor/autoload.php';

        $qr_options = new QROptions(
            [
                'eccLevel' => QRCode::ECC_L,
                'outputType' => QRCode::OUTPUT_MARKUP_SVG,
                'version' => 5,
            ]
        );
        $qrcode = (new QRCode($qr_options))->render('https://example.com/certificates/' . $certificate_number);

        if (!isset($error)) {
            $body = '<img src="images\sreda_logo.png" alt="" width="400" height="100">'
                    . '<br/>'
                    . '<br/>'
                    . '<h1 style="text-align: center; color: #3A588F;">Course certificate </h1>'
                    . '<hr/>'
                    . '<h2 style="color: #3A588F">Certificate number: <span style="color: #000000">' . $certificate_number . '</span></h2>'
                    . '<h2 style="color: #3A588F">Course name: <span style="color: #000000">' . $course_name . '</span></h2>'
                    . '<h2 style="color: #3A588F">Student name: <span style="color: #000000">' . $student_name . '</span></h2>'
                    . '<h2 style="color: #3A588F">Course end date: <span style="color: #000000">' . $end_date . '</span></h2>'
                    . '<hr/>'
                    . '<div style="text-align: center"><img src="' . $qrcode . '" alt="QR Code" width="350" height="350" style="text-align: center"></div>'
                    . '<br/>'
                    . '<br/>'
                    . '<div>'. date("F j, Y") .'  Ukraine</div>';

            $mpdf = new Mpdf([
                'debug' => true,
                'allow_output_buffering' => true
            ]);

            $mpdf->WriteHTML($body);
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->list_indent_first_level = 0;
            $mpdf->SetWatermarkText('BUSINESS SREDA');
            $mpdf->showWatermarkText = true;
            $mpdf->watermarkTextAlpha = 0.1;
            ob_end_clean();
            $mpdf->Output();
        }
    }
    ?>

</head>
<body>
<nav class="navbar navbar-light bg-light mb-20">
    <div class="container">
        <div class="navbar-brand col-sm-4 offset-sm-4">
            <img src="images\sreda_logo.png" alt="" width="150" height="50">
            <div class="text-center"><h3>Course certificate generation</h3></div>
        </div>
    </div>
</nav>
<div id="data_form_main">
    <div class="container">
        <div class="col-sm-4 offset-sm-4">
            <form id="certificate_form" action="" method="post" class="needs-validation" novalidate">
            <div class="form-group mb-20">
                <label for="form_certificate_number">Certificate number:</label>
                <input type="text" class="form-control" id="form_certificate_number" name="certificate_number"
                       aria-describedby="certificateNum" placeholder="Enter certificate number" required>
                <small id="certificateNum" class="form-text text-muted">It will be number of certificate.</small>
            </div>
            <div class="form-group mb-20">
                <label for="form_course_name">Course name:</label>
                <input type="text" class="form-control" id="form_course_name" name="course_name"
                       placeholder="Enter course name" required>
            </div>
            <div class="form-group mb-20">
                <label for="form_student_name">Student name:</label>
                <input type="text" class="form-control" id="form_student_name" name="student_name"
                       placeholder="Enter student name" required>
            </div>
            <div class="form-group mb-20">
                <label for="end_date">Course end date:</label>
                <input type="date" class="form-control" id="form_end_date" name="end_date" required>
            </div>

            <button type="submit" name="submit" class="btn btn-outline-primary col-sm-6 offset-sm-3">Get certificate</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
