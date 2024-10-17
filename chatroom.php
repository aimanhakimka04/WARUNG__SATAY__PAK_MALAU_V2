<?php


use Google\Service\CloudControlsPartnerService\Console;

$role = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null;

if ($role == 'admin') {
    $userid = $_SESSION['login_id'];
} else if ($role == 'customer') {
    $userid = $_SESSION['login_user_id'];
} else {
    $userid = 0;
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <!-- font awesome icon cdn-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
    <div id="test">
        <div class="child" id="chatbot">
            <div class="header">
                <div class="h-child">
                    <img src="images/logo2.png" alt="avatar" class="logo-bot">
                    <div>
                        <span class="name">Chatbot</span>
                        <br>
                        <span style="color:lawngreen">online</span>
                    </div>
                </div>

            </div>

            <div id="chat-box">

            </div>
            <div class="footer">
                <span>powered by @pakmalausatay</span>
            </div>
        </div>
    </div>
</body>


<!-- <div id="messages">
            <?php /*
            foreach ($chat_data as $row) {
                if ($userid == $row['userid'] && $role == $row['role_user']) {
                    echo '<div class="message right"><div class="bubble right"><strong>You:</strong> ' . $row['msg'] . '</div><div class="timestamp">' . $row['created_on'] . '</div></div>';
                } else {
                    echo '<div class="message left"><div class="bubble left"><strong>testing user:</strong> ' . $row['msg'] . '</div><div class="timestamp">' . $row['created_on'] . '</div></div>';
                }
            }*/
            ?>
        </div> -->

<!-- <div id="input-group">
            <input type="text" id="user-input" placeholder="Type a message..." />
            <button class="button-send" onclick="sendMessage()">Send</button>
        </div>-->

<script src="chatbot_userdata.php"></script>
<script>
    //ai system start 
    /**/
    //run initChat() when document is ready

    window.onload = function() {
        initChat();
        storeWebSocketLink();
    };



    /* var data = {
    chatinit: {
        title: ["Hello <span class='emoji'> &#128075;</span>", "Welcome to Warung Satay Pak Malau", "How can I assist you today?"],
        options: ["Order Satay <span class='emoji'> &#127844;</span>", "Track Order <span class='emoji'> &#128347;</span>", "Payment Issues <span class='emoji'> &#128179;</span>", "Customer Support <span class='emoji'> &#128100;</span>"]
    },
    order: {
        title: ["Great! What type of satay would you like to order?"],
        options: ["Chicken Satay", "Beef Satay", "Lamb Satay", "Mixed Satay"],
        url: {
            more: "#",
            link: ["#", "#", "#", "#"]
        }
    },
    track: {
        title: ["Please enter your order number to track your delivery"],
        options: [],
        url: {
            more: "#",
            link: ["#"]
        }
    },
    payment: {
        title: ["We noticed you are facing payment issues", "Please select an option below to get help"],
        options: ["Failed Transaction", "Refund Request", "Payment Method Help", "Other Issues"],
        url: {
            more: "#",
            link: ["#", "#", "#", "#"]
        }
    },
    support: {
        title: ["How can we assist you further?"],
        options: ["Speak to a Customer Representative", "FAQs", "Operating Hours", "Location & Contact"],
        url: {
            more: "#",
            link: ["#", "#", "#", "#"]
        }
    },
    chicken: {
        title: ["Here are our Chicken Satay options"],
        options: ["Chicken Satay - Regular", "Chicken Satay - Spicy", "Chicken Satay - Peanut Sauce"],
        url: {
            more: "#",
            link: ["#", "#", "#"]
        }
    },
    beef: {
        title: ["Here are our Beef Satay options"],
        options: ["Beef Satay - Regular", "Beef Satay - Spicy", "Beef Satay - Soy Sauce"],
        url: {
            more: "#",
            link: ["#", "#", "#"]
        }
    },
    lamb: {
        title: ["Here are our Lamb Satay options"],
        options: ["Lamb Satay - Regular", "Lamb Satay - Spicy", "Lamb Satay - Herb Marinade"],
        url: {
            more: "#",
            link: ["#", "#", "#"]
        }
    },
    mixed: {
        title: ["Here are our Mixed Satay options"],
        options: ["Mixed Satay - Chicken & Beef", "Mixed Satay - Chicken & Lamb", "Mixed Satay - Beef & Lamb"],
        url: {
            more: "#",
            link: ["#", "#", "#"]
        }
    }
}*/

    const userid = <?php echo $userid; ?>;
    const role = "<?php echo $role; ?>";

    document.getElementById("init").addEventListener("click", showChatBot);
    var cbot = document.getElementById("chat-box");

    var len1 = data.chatinit.title.length; // to get the length of the array

    function showChatBot() {
        console.log(this.innerText);
        if (this.innerText == 'CHAT NOW') {
            document.getElementById('test').style.display = 'block';
            document.getElementById('init').innerText = 'CLOSE CHAT';
        } else {
            document.getElementById('test').style.display = 'none';
            document.getElementById('init').innerText = 'CHAT NOW';
        }
    }

    function initChat() {
        j = 0;
        cbot.innerHTML = '';
        for (var i = 0; i < len1; i++) {
            setTimeout(handleChat, (i * 500));
        }
        setTimeout(function() {
            showOptions(data.chatinit.options)
        }, ((len1 + 1) * 500))
    }

    var j = 0;

    function handleChat() {
        var elm = document.createElement("p");
        elm.innerHTML = data.chatinit.title[j];
        elm.setAttribute("class", "msg");
        cbot.appendChild(elm);
        j++;
        handleScroll();
    }

    function showOptions(options) {
        for (var i = 0; i < options.length; i++) {
            var opt = document.createElement("span");
            var inp = '<div>' + options[i] + '</div>';
            opt.innerHTML = inp;
            opt.setAttribute("class", "opt");
            opt.addEventListener("click", handleOpt);
            cbot.appendChild(opt);
            handleScroll();
        }
        // Add "Main Menu" option
        var mainMenuOpt = document.createElement("span");
        mainMenuOpt.innerHTML = '<div>Main Menu</div>';
        mainMenuOpt.setAttribute("class", "opt");
        mainMenuOpt.addEventListener("click", initChat);
        cbot.appendChild(mainMenuOpt);
        handleScroll();
    }

    function handleOpt() {
        var str = this.innerText;
        var findText = str.split(" ")[0].toLowerCase();

        document.querySelectorAll(".opt").forEach(el => {
            el.remove();
        });

        var elm = document.createElement("p");
        elm.setAttribute("class", "test");
        var sp = '<span class="rep">' + this.innerText + '</span>';
        elm.innerHTML = sp;
        cbot.appendChild(elm);

        if (findText === "customer") {
            // Special case for "Customer Support"
            var supportElm = document.createElement("p");
            supportElm.innerHTML = "<p>👋 <strong>Need Assistance?</strong><br>Click on the link provided below to connect with our customer service team for help!</p><p>🔗 <a href='http://localhost/' style='color: blue; text-decoration: underline;'>Connect with Customer Service</a></p>";
            supportElm.setAttribute("class", "msg");
            cbot.appendChild(supportElm);
            var mainMenuOpt = document.createElement("span");
            mainMenuOpt.innerHTML = '<div>Main Menu</div>';
            mainMenuOpt.setAttribute("class", "opt");
            mainMenuOpt.addEventListener("click", initChat);
            cbot.appendChild(mainMenuOpt);
        } else {
            var tempObj = data[findText];
            handleResults(tempObj.title, tempObj.options, tempObj.url);
        }
    }

    function handleResults(title, options, url) {
        for (let i = 0; i < title.length; i++) {
            setTimeout(function() {
                handleDelay(title[i]);
            }, i * 500)
        }

        if (url.more) {
            setTimeout(function() {
                handleOptions(options, url);
            }, title.length * 500);
        } else {
            setTimeout(function() {
                showOptions(options);
            }, title.length * 500);
        }
    }

    function handleOptions(options, url) {
        for (var i = 0; i < options.length; i++) {
            var opt = document.createElement("span");
            var inp = '<a class="m-link" href="' + url.link[i] + '">' + options[i] + '</a>';
            opt.innerHTML = inp;
            opt.setAttribute("class", "opt");
            cbot.appendChild(opt);
        }
        handleScroll();
    }

    function handleDelay(title) {
        var elm = document.createElement("p");
        elm.innerHTML = title;
        elm.setAttribute("class", "msg");
        cbot.appendChild(elm);
    }

    function handleScroll() {
        var elem = document.getElementById('chat-box');
        elem.scrollTop = elem.scrollHeight;
    }




    //on open page run storeWebSocketLink() to store the websocket link
</script>

<style>
    #test {
        display: none;
        position: fixed;
        bottom: 10px;
        right: 0;
        margin: 1rem;
        z-index: 1000;

    }

    #input-group {
        display: none;
    }

    .close-chat {
        border: none !important;
        color: grey !important;
        font-size: 25px !important;
        cursor: pointer !important;
        right: 15px !important;
        top: 10px !important;
        position: absolute !important;

    }

    .refBtn {
        position: absolute;
        bottom: 1rem;
        right: 1rem;
        background: none;
        border: none;
        border-radius: 50%;
        color: indianred;
        font-size: 18px;
        cursor: pointer;
    }

    .desc p {
        color: rgb(133, 153, 168);
        margin: 0;
        font-weight: 600;
    }

    .text {
        font-size: 65px;
        font-weight: 800;
        color: cadetblue;
        margin: 0;
    }

    .parent {
        position: relative;
        height: 100%;
        padding: 0 7rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .bot-img {
        width: 20rem;
        height: 20rem;
    }

    .child {
        box-shadow: 0 0 2px salmon;
        border-radius: 15px;
        height: 30rem;
        width: 20rem;
        margin: auto;
        background: white;
    }

    .header img {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        margin: 0 0.5rem;
        border: 1px solid rgb(231, 231, 231);
        object-fit: contain;
        /* This ensures the logo fits within the circle */
        background-color: white;
        /* Optional: This adds a white background */
        padding: 0px;
        /* Optional: Adjusts padding to fit the logo better */
    }

    .header {
        position: absolute;
        top: 0;
        display: flex;
        align-items: center;
        border-bottom: 1px solid whitesmoke;
        background: white;
        width: 20rem;
        padding: 5px 0;
        border-top-right-radius: 15px;
        border-top-left-radius: 15px;
        z-index: 1;
        box-shadow: 0 0 2px rgb(175, 175, 175);
    }

    .h-child {
        display: flex;
        align-items: center;
    }

    /* Ensure this is added or updated in your existing CSS */



    .header span {
        font-size: 13px;
        margin: 0;
        padding: 0;
    }

    .refBtn {
        position: absolute;
        bottom: 1rem;
        right: 1rem;
        background: none;
        border: none;
        border-radius: 50%;
        color: indianred;
        font-size: 18px;
        cursor: pointer;
    }

    .name {
        font-weight: 600;
    }

    .footer {
        position: absolute;
        bottom: 0;
        width: 20rem;
        background: white;
        border-bottom-left-radius: 15px;
        border-bottom-right-radius: 15px;
        color: indianred;
        padding: 15px 0;
        text-align: center;
        font-size: 14px;
        box-shadow: 0 0 3px rgb(153, 153, 153);
    }

    .sent-msg {
        position: absolute;
        width: 20rem;
        background: white;
        padding: 15px 0;
        text-align: center;
        font-size: 14px;
        box-shadow: 0 0 3px rgb(153, 153, 153);
    }

    .sent-msg input {
        width: 70%;
        padding: 10px;
        border: 1px solid rgb(231, 231, 231);
        border-radius: 15px;
        margin: 0 0.5rem;
    }

    .button-send span,
    .button-send img {
        background: none;
        border: none;
        cursor: pointer;
    }

    .button-send img {
        width: 30px;
        height: 30px;
    }


    #chat-box {
        position: relative;
        top: 40px;
        padding: 25px 10px;
        font-size: 12px;
        height: 24.2rem;
        overflow: auto;
        background: rgb(224, 241, 253);
        text-align: center;
        margin-bottom: 30px;
    }

    /* these classes will be used in javascript file */
    .msg {
        background: white;
        padding: 5px 15px;
        border-radius: 15px;
        width: max-content;
        font-size: 14px;
        color: lightslategrey;
        box-shadow: 0 0 5px rgb(226, 226, 226);
        max-width: 65%;
        text-align: left;
        transition: opacity 0.3s ease-in-out;
        /* Smooth fade-in transition */
    }


    .test {
        text-align: right;
        margin: 20px 0;
    }

    .rep {
        background: rgb(253, 243, 224);
        color: lightslategray;
        padding: 5px 15px;
        border-top-right-radius: 15px;
        border-bottom-left-radius: 15px;
        border-top-left-radius: 15px;
        font-size: 14px;
        box-shadow: 0 0 5px rgb(211, 211, 211);
    }

    .opt {
        padding: 5px 20px;
        columns: lightsalmon;
        border: 1px solid blueviolet;
        border-radius: 1rem;
        margin: 0.3rem 0.5rem;
        display: inline-block;
        cursor: pointer;
        font-weight: 500;
        background: white;
        text-align: center;
        font-size: 14px;
        color: blueviolet;
    }

    .link {
        text-decoration: none;
        display: block;
        text-align: center;
        color: aliceblue !important;
        background: blueviolet;
    }

    .m-link {
        text-decoration: none;
    }

    .link:active {
        background: white;
        border: 1px solid blueviolet;
        color: blueviolet;
    }
</style>