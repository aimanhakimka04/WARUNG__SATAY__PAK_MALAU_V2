<?php


use Google\Service\CloudControlsPartnerService\Console;

$role = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null;

if ($role == 'admin') {
    $userid = $_SESSION['login_id'];
} else if ($role == 'customer') {
    $userid = $_SESSION['login_user_id'];
}else{
    $userid = 0;
}

require('database/ChatRooms.php');

$chat_object = new ChatRooms();

$chat_data = $chat_object->get_all_chat_data();
?>



<div id="test" >
    <div class="child" id="chatbot">
        <div class="header">
            <div class="h-child">
                <img src="images/logo2.png" alt="avatar" class="logo-bot">
                <div>
                    <span class="name">Chatbot</span>
                    <br>
                    <span style="color:lawngreen">online</span>
                    
                </div>
            </div><span class="close-chat" onclick="showChatBot()">X</span>
            <div>
                <!--<button class="refBtn"><i class="fa fa-refresh" onclick="initChat()"></i></button>-->
            </div>
        </div>

        <div id="chat-box">

        </div>
        <div id="input-group" class="sent-msg">
            <input type="text" id="user-input" placeholder="Type a message..." />
            <span class="button-send" onclick="sendMessage()"><img src="images/sent.png" /></bu>
        </div>
        <div class="footer" >
            <span>powered by @pakmalausatay</span>
        </div>
    </div>
</div>

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
    //run initChat() when document is ready

    window.onload = function() {
        initChat();
    };


    /*var data = {
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



    document.getElementById("init").addEventListener("click", showChatBot);
    var cbot = document.getElementById("chat-box");
    
    var len1 = data.chatinit.title.length; // to get the length of the array

    function showChatBot() {
        
        console.log(this.innerText);
        if(this.innerText == 'CHAT NOW') {
            document.getElementById('test').style.display = 'block';
            document.getElementById('init').innerText = 'CLOSE CHAT';
        } else {
            document.getElementById('test').style.display = 'none';
            document.getElementById('init').innerText = 'CHAT NOW';
            // location.reload();
        }
    }

    function initChat() {
    j = 0;
    cbot.innerHTML = ''; // Clear previous chat
    for (var i = 0; i < len1; i++) {
        setTimeout(handleChat, i * 1000); // Add a 1-second delay between messages
    }
    setTimeout(function() {
        showOptions(data.chatinit.options);
    }, ((len1 + 1) * 1000)); // Show options after the last message
}

var j = 0;

function handleChat() {
    console.log(j);
    var elm = document.createElement("p");
    elm.innerHTML = data.chatinit.title[j];
    elm.setAttribute("class", "msg");

    // Append message with a fade-in effect
    elm.style.opacity = 0;
    cbot.appendChild(elm);
    setTimeout(() => {
        elm.style.opacity = 1; // Gradually show the message
    }, 300); // Delay before making it fully visible

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
    }

    function handleOpt() {
    console.log(this);
    var str = this.innerText;
    var textArr = str.split(" ");
    var findText = textArr[0];

    document.querySelectorAll(".opt").forEach(el => {
        el.remove();
    });

    var elm = document.createElement("p");
    elm.setAttribute("class", "test");
    var sp = '<span class="rep">' + this.innerText + '</span>';
    elm.innerHTML = sp;
    cbot.appendChild(elm);

    console.log(findText.toLowerCase());
    var tempObj = data[findText.toLowerCase()];

    if (findText.toLowerCase() === "customer") {
        // Show the input group if "Customer Support" is selected
        document.getElementById("input-group").style.display = "block";
        document.getElementById("chatbot").style.height = "34rem";
    } else {
        // Hide the input group and display a message to the user
        document.getElementById("input-group").style.display = "none";
        document.getElementById("chatbot").style.height = "30rem";
        var elm = document.createElement("p");
        elm.innerHTML = "Please click the Customer Support option.";
        elm.setAttribute("class", "msg");
        cbot.appendChild(elm);
    }

    handleResults(tempObj.title, tempObj.options, tempObj.url);
    handleScroll();
}


    function handleDelay(title) {
        var elm = document.createElement("p");
        elm.innerHTML = title;
        elm.setAttribute("class", "msg");
        cbot.appendChild(elm);
    }


    function handleResults(title, options, url) {
        for (let i = 0; i < title.length; i++) {
            setTimeout(function() {
                handleDelay(title[i]);
            }, i * 500)

        }

        const isObjectEmpty = (url) => {
            return JSON.stringify(url) === "{}";
        }

        if (isObjectEmpty(url) == true) {
            console.log("having more options");
            setTimeout(function() {
                showOptions(options);
            }, title.length * 500)

        } else {
            console.log("end result");
            setTimeout(function() {
                handleOptions(options, url);
            }, title.length * 500)

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
        var opt = document.createElement("span");
        var inp = '<a class="m-link" href="' + url.more + '">' + 'See more</a>';

        const isObjectEmpty = (url) => {
            return JSON.stringify(url) === "{}";
        }

        console.log(isObjectEmpty(url));
        console.log(url);
        opt.innerHTML = inp;
        opt.setAttribute("class", "opt link");
        cbot.appendChild(opt);
        handleScroll();
    }

    function handleScroll() {
        var elem = document.getElementById('chat-box');
        elem.scrollTop = elem.scrollHeight;
    }

    //end of ai system

    const userid = <?php echo $userid; ?>;
    const role = "<?php echo $role; ?>";

    function getWebSocketURL() {
        const hostname = window.location.hostname;
        if (hostname === 'localhost') {
            return 'ws://localhost:8080';
        } else {
            return 'ws://206.189.84.162:8080';
        }
    }

    // Generate a random client ID
    function generateClientId() {
        return 'client-' + Math.random().toString(36).substr(2, 9);
    }

    const clientId = generateClientId();
    var conn = new WebSocket(getWebSocketURL());

    conn.onopen = function(e) {
        console.log("Connection established!");
    };

    conn.onmessage = function(e) {
        console.log(e.data);

        var data = JSON.parse(e.data);

        const messagesDiv = document.getElementById('messages');
        const timestamp = new Date().toLocaleTimeString();

        if (data.userid === userid) {
            messagesDiv.innerHTML += `<div class="message right"><div class="bubble right"><strong>You:</strong> ${data.msg}</div><div class="timestamp">${timestamp}</div></div>`;
        } else {
            messagesDiv.innerHTML += `<div class="message left"><div class="bubble left"><strong>${data.usremail}:</strong> ${data.msg}</div><div class="timestamp">${timestamp}</div></div>`;
        }

        messagesDiv.scrollTop = messagesDiv.scrollHeight;
    };

    document.getElementById('user-input').addEventListener('keydown', function(event) {
        if (event.key === 'Enter') {
            sendMessage();
        }
    });

    function sendMessage() {
        const userInput = document.getElementById('user-input').value;
        const timestamp = new Date().toLocaleTimeString();

        if (userInput.trim() === '') return;

        var data = {
            role: role,
            userid: userid,
            clientId: clientId,
            msg: userInput
        }

        conn.send(JSON.stringify(data));

        const messagesDiv = document.getElementById('messages');
        messagesDiv.scrollTop = messagesDiv.scrollHeight;
        document.getElementById('user-input').value = '';
    }
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
        top : 10px !important;
        position: absolute !important; 
        
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
        width: 16rem;
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
        width: 16rem;
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
        width: 16rem;
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
        width: 16rem;
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

    .button-send span, .button-send img {
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
    opacity: 0;
    transition: opacity 0.3s ease-in-out; /* Smooth fade-in transition */
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


