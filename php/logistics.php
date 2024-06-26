<?php include 'security.php'; ?>
<html>
<head>
    <title>Ferrari Logistics</title>
    <link rel="icon" href="../img/logo/white-horse.svg" type="image/x-icon">

    <link rel="stylesheet" type="text/css" href="../css/AllAroundF1.css">
    <link rel="stylesheet" type="text/css" href="../css/backend.css">
    <link rel="stylesheet" type="text/css" href="../css/dynamic-dashboard.css">
    <link rel="stylesheet" type="text/css" href="../css/logistics.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <script src="../js/logistics.js"></script>
</head>
<body>
    <nav>
        <div class="logo">
            <a href="backend.php"><img src="../img/logo/white-horse.svg" alt=""></a>
        </div>
        <div class="links">
            <ul>
                <?php include 'navbar-backend.php'; ?>
            </ul>
        </div>
        <div class="account">
            <ul>
                <li>
                    <a href="./logout.php">
                        <i class="fa-regular fa-arrow-right-from-bracket"></i>
                        <span class="nav-text">Logout</span>
                    </a>
                </li>
                <li>
                    <a href="./account-backend.php">
                        <i class="fa-regular fa-user"></i>
                        <span class="nav-text">Account</span>
                    </a>
                </li>
            </ul>
        </div>
    </nav>
    <div id="screen-overlay" class="screen-overlay">
        <div id="popup" class="popup">
            <div class="popup-header">
                <h3 class="popup-title"></h3>
                <i class="bi bi-x" onclick="closePopup()"></i>
            </div>
            <div id="popup-content" class="popup-content">
            </div>
        </div>
    </div>
    <section id="dynamic-dashboard">
        <div class="dynamic-dashboard-grid"> 
            <div class="dynamic-dashboard-card grid-col-span-4 grid-row-span-8">
                <div class="list">
                    <div class="list-header">
                        <div class="title">
                            <div class="title-text">
                                <i class="fa-regular fa-truck-fast"></i>
                                <h2>Logistics</h2>
                            </div>
                            <p>Manage the logistics of the Scuderia Ferrari</p>
                        </div>
                        <button class="button-primary button-squadrato popup-open"><i class="fa-regular fa-plus"></i></i>New shift</button>
                    </div>
                    <div class="list-card">
                        <div class="big-card">
                            <div class="card">
                                <div class="card-icon">
                                    <i class="fa-regular fa-truck-clock"></i>
                                </div>
                                <div class="card-data">
                                    <p>Pending shifts</p>
                                    <span>
                                        <?php
                                            include 'db.php';
                                            $sql = "SELECT COUNT(*) AS total FROM logistica WHERE data_partenza > NOW()";
                                            $result = $conn->query($sql);
                                            $data = mysqli_fetch_assoc($result);
                                            echo $data['total'];
                                        ?>
                                    </span>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-icon">
                                    <i class="fa-regular fa-truck-arrow-right"></i>
                                </div>
                                <div class="card-data">
                                    <p>In progress shifts</p>
                                    <span>
                                        <?php
                                            include 'db.php';
                                            $sql = "SELECT COUNT(*) AS total FROM logistica WHERE data_partenza < NOW() AND data_arrivo > NOW()";
                                            $result = mysqli_query($conn, $sql);
                                            $data = mysqli_fetch_assoc($result);
                                            echo $data['total'];
                                        ?>
                                    </span>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-icon">
                                    <i class="fa-regular fa-box-circle-check"></i>
                                </div>
                                <div class="card-data">
                                    <p>Completed shifts</p>
                                    <span>
                                        <?php
                                            include 'db.php';
                                            $sql = "SELECT COUNT(*) AS total FROM logistica WHERE data_arrivo < NOW()";
                                            $result = mysqli_query($conn, $sql);
                                            $data = mysqli_fetch_assoc($result);
                                            echo $data['total'];
                                        ?>
                                    </span>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-icon">
                                    <i class="fa-regular fa-cart-flatbed-boxes"></i>
                                </div>
                                <div class="card-data">
                                    <p>Total shifts</p>
                                    <span>
                                        <?php
                                            include 'db.php';
                                            $sql = "SELECT COUNT(*) AS total FROM logistica";
                                            $result = mysqli_query($conn, $sql);
                                            $data = mysqli_fetch_assoc($result);
                                            echo $data['total'];
                                        ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="filter-card">
                            <div class="card-row">
                                <?php include('./logistics/logistics_count.php'); ?>
                            </div>
                            <div class="search">
                                <input type="text" id="search" placeholder="Search">                            
                                <i class="fa-regular fa-magnifying-glass"></i>
                            </div>
                        </div>
                    </div>
                    <div class="list-content scrollable-section">
                        <div class="table-header">
                            <p>Transport</p>
                            <p>Moved</p>
                            <p>From</p>
                            <p>To</p>
                            <p>Departure date</p>
                            <p>Arrival date</p>
                            <p>Status</p>
                        </div>
                        <div id="list-result" class="table-body">
                            <?php include './logistics/logistics-list.php'; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="dynamic-dashboard-card">
                <div class="time">
                    <img src="../img/logo/logo-scuderia.png" alt="">
                    <div class="clock">
                        <h3 id="time"></h3>
                        <p></p>
                    </div>
                </div>
            </div>
            <div class="dynamic-dashboard-card grid-row-span-7">
                <div id="tab-details" class="tab-details">
                <div class="tab-details-title">
                    <i class="fa-regular fa-magnifying-glass-location"></i>
                        <h3>Shift details</h3>
                    </div>
                    <div id="no-result" class="zero-result" style="display: flex;">
                        <p>Select a shift to see more details</p>
                    </div>
                    <div class="details" id="detailsBlock" style="display: none;">
                        <div class="details-header">
                        <div class="item-img">
                                <i id="icon-transport" class=""></i>
                            </div>
                        </div>
                        <div class="details-content scrollable-section">
                            <h4>Transport Info</h4>
                            <div id="personal-info">
                                <div class="personal-info-row">
                                    <p id="movingId" hidden></p>
                                    <p id="itemId" hidden></p>
                                    <p id="from"><strong>From</strong></p>
                                    <p id="displayFrom"></p>
                                </div>
                                <div class="personal-info-row">
                                    <p id="to"><strong>To</strong></p>
                                    <p id="displayTo"></p>
                                </div>
                                <div class="personal-info-row">
                                    <p id="departure"><strong>Departure</strong></p>
                                    <p id="displayDeparture"></p>
                                </div>
                                <div class="personal-info-row">
                                    <p id="arrival"><strong>Arrival</strong></p>
                                    <p id="displayArrival"></p>
                                </div>
                            </div>
                            <h4>Subject Info</h4>
                            <div id="contract-info">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>
<script>
    function updateTime() {
        var now = new Date();
        var hours = now.getHours();
        var minutes = now.getMinutes();
        var seconds = now.getSeconds();
        var day = now.toLocaleString('it-IT', { weekday: 'long' });
        var date = now.getDate();
        var month = now.toLocaleString('it-IT', { month: 'long' });
        var year = now.getFullYear();

        hours = hours < 10 ? '0' + hours : hours;
        minutes = minutes < 10 ? '0' + minutes : minutes;
        seconds = seconds < 10 ? '0' + seconds : seconds;

        document.getElementById('time').textContent = hours + ' : ' + minutes + ' : ' + seconds;
        document.querySelector('.clock p').textContent = day.charAt(0).toUpperCase() + day.slice(1) + ' ' + date + ' ' + month + ' ' + year;
    }

    setInterval(updateTime, 1000);
</script>
</html>