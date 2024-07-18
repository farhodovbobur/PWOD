<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ish vaqti kiritish</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        p {
            color: red;
        }
    </style>
</head>

<body>
    <?php $total_time = 0; ?>
    <div class="container">

        <h1> PWOT - Personal Work Off Time</h1>

        <form action="index.php" method="POST">
            <div class="row g-3">
                <div class="col">
                    <label>
                        <input type="datetime-local" class="form-control" name="arrived_at">
                    </label>
                </div>
                <div class="col">
                    <label>
                        <input type="datetime-local" class="form-control" name="leaved_at">
                    </label>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">SUBMIT</button>
            <br>
        </form>

        <?php
        require "Daily.php";

        $dsn = 'mysql:host=localhost;dbname=pwot';
        $username = 'root';
        $password = 'root1234';

        $pdo = new PDO($dsn, $username, $password);

        if (isset($_POST['arrived_at']) && isset($_POST['leaved_at'])) {
            if ($_POST['arrived_at'] != "" && $_POST['leaved_at'] != "") {
                $arrived_at = $_POST['arrived_at'];
                $leaved_at = $_POST['leaved_at'];

                $today = new Daily($arrived_at, $leaved_at);

                $work_time = $today->calculate();
                $debt_time = $today->Debt();

                $query = "INSERT INTO pwot (arrival_time, time_to_leave, worked, debt)
                          VALUES (:arrival_time, :time_to_leave, :worked, :debt)";
                $stmt = $pdo->prepare($query);
                $stmt->bindParam(':arrival_time', $arrived_at);
                $stmt->bindParam(':time_to_leave', $leaved_at);
                $stmt->bindParam(':worked', $work_time);
                $stmt->bindParam(':debt', $debt_time);

                $stmt->execute();
            }
        }

        if (isset($_POST['done_id'])) {
            $done_id = $_POST['done_id'];
            $query = "UPDATE pwot SET debt = '00:00' WHERE id = :id";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':id', $done_id);
            $stmt->execute();
        }

        $query = $pdo->query("SELECT * FROM pwot")->fetchAll();

        echo "<h3> Malumotlar Jadval ko'rinishida </h3>";
        echo '<table class="table table-striped-columns">';
        echo '<thead class="thead-dark">';
        echo '<tr>';
        echo '<th>ID</th>';
        echo '<th>Arrived at</th>';
        echo '<th>Leaved at</th>';
        echo '<th>Debt</th>';
        echo '<th>Done</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';

        foreach ($query as $row) {
            $a1 = $row['debt'];
            $a1 = explode(":", $a1);

            echo '<tr>';
            echo '<td>' . $row['id'] . '</td>';
            echo '<td>' . $row['arrival_time'] . '</td>';
            echo '<td>' . $row['time_to_leave'] . '</td>';
            echo '<td>' . $row['debt'] . '</td>';
            echo '<td>';

            if ($a1[1] == '00' && $a1[0] == '00') {
                $row['debt'] = '00'; ?>
                <div class="form-check">
                    <label>
                        <input class="form-check-input" type="checkbox" checked disabled>
                    </label>
                    <label class="form-check-label">
                        Done
                    </label>
                </div>
            <?php } else { ?>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal<?php echo $row['id']; ?>">
                    Done
                </button>

                <div class="modal fade" id="exampleModal<?php echo $row['id']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                Saqlidigan bo'lsangiz qaytarib bo'lmaydi.......!
                            </div>
                            <div class="modal-footer">
                                <form action="index.php" method="POST">
                                    <input type="hidden" name="done_id" value="<?php echo $row['id']; ?>">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
        <?php  }
            echo '</td></tr>';
        }
        echo '</tbody>';
        echo '</table>';

        foreach ($query as $row) {
            $time_parts = explode(":", $row['debt']);
            $total_time += ($time_parts[0] * 60) + $time_parts[1];
        }

        $hour = floor($total_time / 60);
        $minute = $total_time % 60;

        echo '<p class="qizil-tex">Umumiy qarzingiz: ' . "$hour soat $minute daqiqa" . '</p>';
        ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <div class="container">

    </div>
</body>

</html>