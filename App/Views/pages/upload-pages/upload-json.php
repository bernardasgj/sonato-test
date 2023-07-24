<!DOCTYPE html>
<html>
<head>
    <title>Upload JSON File</title>
</head>
<body>
    <h2>Upload JSON File</h2>
    <form action="upload_json_data" method="post" enctype="multipart/form-data">
        Select JSON File:
        <input type="file" name="jsonFile" id="jsonFile">
        <input type="submit" value="Upload" name="submit">
    </form>
</body>
</html>
