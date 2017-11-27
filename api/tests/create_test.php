<?

$postdata = http_build_query(
    array(
            'login' => 'pesok228',
            'password' => 'samosval',
            'first_name' => 'Yiri',
            'last_name' => 'Kostikov',
            'ticket_number' => 'otchislensosixuy',
            'image' => '/a/a/a'
    )
);

$opts = array('http' =>
    array(
        'method'  => 'POST',
        'header'  => 'Content-type: application/x-www-form-urlencoded',
        'content' => $postdata
    )
);

$context  = stream_context_create($opts);

$result = file_get_contents('../visitor/create.php', false, $context);
echo $result;