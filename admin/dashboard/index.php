<?php include('../assets/includes/header.php'); ?>

<main>
    <!-- page title -->
    <div class="ep_section">
        <div class="ep_container">
            <h4 class="welcome_admin_title">Dashboard</h4>
        </div>
    </div>

    <!-- welcome message -->
    <div class="ep_section">
        <div class="ep_container">
            <div class="welcome_card_primary">
                <h5 class="welcome_admin_title">Welcome <?php echo $admin_name; ?>,</h5>
                <p class="welcome_admin_msg">This is your admin panel. It will simplify your business and work.</p>
            </div>
        </div>
    </div>

    <!-- profile photo setting -->
    <div class="ep_section">
        <div class="ep_container widgets">
            <!-- NOTICE -->
            <a href="../notice/" class="ep_grid notice_card h_max ep_card">
                <img src="../assets/img/notice.png" alt="">
                <h5>Notice</h5>
            </a>

            <!-- NEW ENROLLMENT -->
            <a href="../new-enroll/" class="ep_grid notice_card h_max ep_card">
                <img src="../assets/img/enroll.png" alt="">
                <h5>Enrollment</h5>
            </a>
            
            <!-- EXAM -->
            <a href="../exam/" class="ep_grid notice_card h_max ep_card">
                <img src="../assets/img/exam.png" alt="">
                <h5>Exam</h5>
            </a>
            
            <!-- QUESTION BANK -->
            <a href="../question-bank/" class="ep_grid notice_card h_max ep_card">
                <img src="../assets/img/safe.png" alt="">
                <h5>Question Bank</h5>
            </a>

            <!-- ACCOUNT -->
            <a href="../account/" class="ep_grid notice_card h_max ep_card">
                <img src="../assets/img/wallet.png" alt="">
                <h5>Account</h5>
            </a>

            <!-- BLOG -->
            <a href="../blog/" class="ep_grid notice_card h_max ep_card">
                <img src="../assets/img/blog.png" alt="">
                <h5>Article</h5>
            </a>
            
            <!-- Payment -->
            <a href="../payment-list/" class="ep_grid notice_card h_max ep_card">
                <img src="../assets/img/bill.png" alt="">
                <h5>Payment List</h5>
            </a>
            
            <!-- TOKENIZED TRANSACTION -->
            <a href="../tokenized-transaction/" class="ep_grid notice_card h_max ep_card">
                <img src="../assets/img/transaction.png" alt="">
                <h5>Tokenized List</h5>
            </a>
            
            <!-- LOGIN OTP -->
            <a href="../otp-list/" class="ep_grid notice_card h_max ep_card">
                <img src="../assets/img/password.png" alt="">
                <h5>OTP List</h5>
            </a>
            
            <!-- SECRET FILE TOKEN -->
            <a href="../secret-file-token/" class="ep_grid notice_card h_max ep_card">
                <img src="../assets/img/ticket.png" alt="">
                <h5>Secret File Token</h5>
            </a>
            
            <!-- SECRET FILE SOLVE -->
            <a href="../secret-file-solve/" class="ep_grid notice_card h_max ep_card">
                <img src="../assets/img/solve.png" alt="">
                <h5>Secret File Solve</h5>
            </a>
            
            <!-- SECRET FILE STUDENT -->
            <a href="../secret-file-students/" class="ep_grid notice_card h_max ep_card">
                <img src="../assets/img/school.png" alt="">
                <h5>Secret File Entries</h5>
            </a>
            
            <!-- MEDICAL PDF -->
            <a href="../marked-book/" class="ep_grid notice_card h_max ep_card">
                <img src="../assets/img/pdf.png" alt="">
                <h5>Marked Book</h5>
            </a>
            
            <!-- CHAPTER ENROLLED ACCOUNT -->
            <a href="../chapter-enrolled/" class="ep_grid notice_card h_max ep_card">
                <img src="../assets/img/chapter.png" alt="">
                <h5>Chapter Enrolled</h5>
            </a>
            
            <!-- GIFT -->
            <a href="../gift/" class="ep_grid notice_card h_max ep_card">
                <img src="../assets/img/gift.png" alt="">
                <h5>24 & 25 Gifts</h5>
            </a>
            
            <!-- ATTENDANCE -->
            <a href="../employee/" class="ep_grid notice_card h_max ep_card">
                <img src="../assets/img/attendance.png" alt="">
                <h5>Attendance</h5>
            </a>
            
            <!-- NOTES -->
            <a href="../notes/" class="ep_grid notice_card h_max ep_card">
                <img src="../assets/img/notes.png" alt="">
                <h5>Notes</h5>
            </a>
            
            <!-- MANAGE ACCESS -->
            <a href="../result-combine/" class="ep_grid notice_card h_max ep_card">
                <img src="../assets/img/data-integration.png" alt="">
                <h5>Result Combine</h5>
            </a>
            <!-- MANAGE ACCESS -->
            <a href="../graph/" class="ep_grid notice_card h_max ep_card">
                <img src="../assets/icon/graph.png" alt="">
                <h5>Graph</h5>
            </a>
        </div>
    </div>
</main>

<?php include('../assets/includes/footer.php'); ?>