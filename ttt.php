<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <title>Document</title>
</head>
<body>
<form method="post">
    
<div class="input-group mb-3 col-3">
    <div class="input-group-prepend">
        <span class="input-group-text" id="basic-addon1">Your Username:</span>
    </div>
    <input type="text" class="form-control" id="username" name="user" required aria-label="User" aria-describedby="basic-addon1">
    </div>
   
    <div class="input-group mb-3 col-3">
    <div class="input-group-prepend">
        <span class="input-group-text" id="basic-addon1">Repository Name:</span>
    </div>
    <input type="text" class="form-control" id="reponame" name="reponame" required aria-label="Reponame" aria-describedby="basic-addon1">
    </div>

    <div class="input-group mb-3 col-3">
    <div class="input-group-prepend">
        <span class="input-group-text" id="basic-addon1">Repository Owner:</span>
    </div>
    <input type="text" class="form-control" id="repoowner" name="repoowner" required aria-label="Repoowner" aria-describedby="basic-addon1">
    </div>
    
    <div class="input-group mb-3 col-3">
    <div class="input-group-prepend">
        <span class="input-group-text" id="basic-addon1">Access Token:</span>
    </div>
    <input type="text" class="form-control" id="accesstoken" name="accesstoken" required aria-label="Accesstoken" aria-describedby="basic-addon1">
    </div>
    
    <div class="input-group mb-3 col-3">
    <div class="input-group-prepend">
        <span class="input-group-text" id="basic-addon1">Branch Name:</span>
    </div>
    <input type="text" class="form-control" id="branchname" name="branchname" required aria-label="Branchname" aria-describedby="basic-addon1">
    </div>

   
    
    <div class="input-group mb-3 col-3">
    <div class="input-group-prepend">
        <span class="input-group-text" id="basic-addon1">Date (YYYY-MM-DD):</span>
    </div>
    <input type="text" class="form-control" id="date" name="date" required aria-label="Date" aria-describedby="basic-addon1">
    </div>
    
    
    
    <input type="submit" name="submit" class="btn btn-primary ml-3" value="Get Commits">
</form>
<div class="ml-3 mt-3">
    <?php
        if(isset($_POST['submit'])){
        // Get the input values from the form
        $reponame = $_POST['reponame'];
        $repoowner = $_POST['repoowner'];
        $token = $_POST['accesstoken'];
        $branchname = $_POST['branchname'];
        $user = $_POST['user'];
        $date = $_POST['date'];

        $since_date = $date . 'T00:00:00Z';
        $until_date = $date . 'T23:59:59Z';

        $url = "https://api.github.com/repos/$repoowner/$reponame/commits?author=$user&sha=$branchname&since=$since_date&until=$until_date";

        // Set the API endpoint URL

        // Send the API request using cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization: token ' . $token,
            'User-Agent: My-App'
        ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);

        // Process the API response
        $data = json_decode($result, true);

        if(empty($data)){
            echo "No commits found by user '$user' in branch '$branchname' after date '$date'.";
        }
        else{
            $formatted_date = date('jS F, Y', strtotime($date));
            echo "Today's ( $formatted_date ) activities are -";
            foreach ($data as $key => $commit) {
                
                $message = $commit['commit']['message'];
                if(strpos($message, 'Merge branch') === 0){
                    continue; //skip merge commits
                }
                $link = $commit['html_url'];
                $key++;
                echo "<p>$key) $message <br/>- $link</p>";
            }
        }
        }
    ?>
</div>
</body>
</html>
