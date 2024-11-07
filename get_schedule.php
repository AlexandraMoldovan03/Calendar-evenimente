require_once('db-connect.php');
$code = $_POST['code'];

$schedules = $conn->query("SELECT * FROM `schedule_list` WHERE `code` = '$code'");
$sched_res = [];
while ($row = $schedules->fetch_assoc()) {
    $row['sdate'] = date("F d, Y h:i A", strtotime($row['start_datetime']));
    $row['edate'] = date("F d, Y h:i A", strtotime($row['end_datetime']));
    $sched_res[$row['id']] = $row;
}
echo json_encode($sched_res);
$conn->close();
