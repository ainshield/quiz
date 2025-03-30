<?php
session_start();

$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest'; 
$password = isset($_SESSION['password']) ? $_SESSION['password'] : ''; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: "Montserrat", sans-serif;
            margin: 0;
            height:auto;
            background-color: #e0f7fa;
            color: #333;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #00796b;
            color: white;
            padding: 15px 30px;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.1);
        }

        .logo {
            font-size: 1.8em;
            font-weight: 600;
        }

        .user-info {
            display: flex;
            align-items: center;
        }

        .profile-icon {
            width: 40px;
            height: 40px;
            background-color: white;
            color: #00796b;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2em;
            font-weight: 600;
            cursor: pointer;
        }

        .dashboard {
            display: flex;
            height: calc(100vh - 80px);
        }

        .sidebar {
            background-color: #004d40;
            color: white;
            width: 250px;
            padding: 20px;
            height:200vh;
            box-shadow: 3px 0 8px rgba(0, 0, 0, 0.2);
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
        }

        .sidebar ul li {
            padding: 12px;
            margin: 10px 0;
            background-color: #00695c;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
            font-weight: 500;
        }

        .sidebar ul li:hover {
            background-color: #00897b;
        }

        .content {
            flex: 1;
            padding: 30px;
        }

        .card {
            background-color: white;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.1);
        }

        .card h3 {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">Oral Communication</div>
        <div class="user-info">
            <div class="profile-icon" id="profile-icon">
                <?php echo strtoupper(substr($username, 0, 1)); ?>
            </div>
        </div>
    </div>
    
    <div class="dashboard">
        <div class="sidebar">
            <ul>
                <li>Lesson 2: Understanding Speech</li>
            </ul>
        </div>

        <div class="content">
            <div class="card">
                <h3>What is Communication?</h3>
                <p>What is communication? 
Communicare is a Latin word which means to share and to make something common. It is 
one of the overworked terms in English language. 
As a working definition, it is a process by which people transmit information, share verbal and 
nonverbal messages and create meaning with each other. It is an exaggeration to say that 
communication function is the means by which social inputs are fed into. 
Communication is the transfer of information from a sender to a receiver with the information 
being understood by the receiver..</p>
            </div>

            <div class="card">
                <h3>Why is Communication Important?</h3>
                <p>Why is communication important? 
In the past, the importance of communication in organized effort has been recognized by many 
popular authors. Chester I. Barnard viewed communication as a means by which people are 
linked together in any organization. In order to accomplish a common goal. This remains the 
same vital function of communication over the years. Honestly, group activity would be 
impossible without communication. It is because coordination and change cannot be achieved. 
Communication helps understand people better, removes misunderstanding, and creates 
clarity of thoughts and expression and educates people. 
In its broader sense, the purpose of communication is to effect change among people. 
Communication also facilitates managerial functions as it relates to internal and external 
environment. Through information exchange, people or any group of people becomes an open 
system interacting with its environment, a fact whose importance is emphasized. 
Even in the animal kingdom, communication is very vital to their life and survival. They 
communicate thru behavior, sounds, tactile, and chemical signals emitted from their body. 
Communication is fundamental to success in whatever field of interest you are in. Proper 
communication makes the business a success. Your ability to say what you feel, express your 
ideas and persuade others to believe in you and convince them to react or respond to you 
which are factors to a successful career. Thus, communication is vital in every field of 
endeavor.</p>
            </div>


            <div class="card">
                <h3>Why is Communication Important?</h3>
                <p>What are the functions of communication? 
a. Regulation and control 
● There is limiting or regulating of something 
● It is the control when there is a power of influence or affects people’s behavior or the 
course of events 
● It is used mainly by a person in authority to regulate or direct others under them 
Examples: 
- students listening to teacher’s instructions
- friends giving advice 
- doctor’s medical advice
- parent requesting errands from children 
b. Social Interaction 
● Primary reason why people communicate is to produce social interaction in our daily 
living. Human beings develop and maintain bonds, intimacy, relations, and 
associations, The family members interact with each other more frequently these days. 
c. Motivation 
● It refers to a person using language or symbols to express desires, needs, wants, likes 
and dislikes, inclinations, choices and aspirations. This is needed to persuade another 
person to change his/her opinion, attitude, or behavior. It could be a way of expressing
one’s ambitions, talking about preferences, making petitions, and more.
Colegio de Los Baños – ORAL COMMUNICATION
d. Information dissemination 
● It can be used for getting and giving information. This function is used when the 
speaker wants to make others aware of certain data, concepts, and processes –
knowledge that may be useful to them. These can be done when people use:. 
-commands / instructions
-asks questions 
-give information / details 
-presenting general statements 
● Communication is the transfer of information from a sender to a receiver with the 
information being understood by the receiver. 
e. Emotional Expression 
● Communication facilities people’s expression of their feelings and emotions. . It is an 
important signal that conveys a variety of information regarding a person’s state of 
mind and his/her intentions. Humans’ express emotions verbally and nonverbally.
Generally, the central part of who we are, the closer the ties, the familiarity to a circle of people 
a person is, the easier he can communicat</p>
            </div>



            
            <div class="card">
                <h3>Why is Communication Important?</h3>
                <p>Nature of Communication 
It is the exchange of thoughts, feelings, expressions and observations among people. 
There is a wide variety of contexts and situations in which communication can take place. 
Examples are face to face conversation, group discussion, a meeting, an interview or class 
recitation, and online classes. Thanks to technology which facilitates our communication,
people transmit messages either verbally or nonverbally using words and phrases, signs or
gestures/actions, sign language. We use mannerisms, style, actions, and how we use words 
whether in spoken or written form. Millennials have their new ways of communicating. New 
words are formed, new meanings are provided in modern communication. It has been said 
that communication is the blood-line of society. There will be no society if there is no 
communication. 
1. Communication is a process. It is cyclical. This tells us that it requires human activity 
since language evolved, newly blended words come to existence. Communication among 
people is active and is a continuing condition of life. A process that changes as the 
communicator’s environment and needs change.
Who would have thought that COVID-19 would affect all people in the world? We learn to 
adapt to a new normal life. We cannot afford to sit down and sulk. We found a new way of 
communicating and adapt to a new learning modality.
Colegio de Los Baños – ORAL COMMUNICATION
2. Communication is interactive. Communication occurs between two or more people (the 
speaker and the receiver). Communication can be expressed through written or spoken words, 
actions, (nonverbal) or both spoken words and nonverbal actions at the same time. 
3. Communication is symbolic. It is the expression of thoughts, feelings, experiences, and 
observations among people. 
The Communication Process 
This involves the sender, transmission of message through a selected channel, and the 
receiver. 
Elements of Communication/Definitions 
1. Speaker/Sender. 
2. Message 
3. Encoding 
4. Channel 
5. Decoding 
6. Listener/Receiver 
7. Feedback 
8. Context /Setting 
9. Noise and barriers 
3 Models of Communication 
The communication model provides an overview of the communication process, 
identifies the critical variables, and shows their relationships. It helps us appreciate the 
importance of the models in our daily life, pinpoint communication problems and take steps to 
solve them, or even better, to avoid the difficulties from occurring in the first place. 
A model is a representation of real-world phenomenon in more abstract terms which 
can be applied to different forms. We use models to simplify the concepts of the 
communication process in graphical form. 
1. Linear Model 
- it is one-way communication 
- sender simply sends the message 
- receiver simply receives the message 
- no feedback 
- noise - the choice of channel is selected and may affect the way the receiver interprets the 
message 
- involves persuasion, not mutual understanding
Colegio de Los Baños – ORAL COMMUNICATION
Advantages Disadvantages 
- good at audience persuasion - communication is not continuous 
- there are intentional results - because no feedback, not interactive 
- used in mass communication - difficult to identify if communication Is effective 
2. Interactive Model 
-it is a two-way communication process 
-used in modern media like internet 
-there is feedback 
-interactive but not simultaneous 
Advantages Disadvantages 
-there is feedback even in mass communication -feedback is delayed 
-uses new communication channels -sender and receiver may not know who the 
other person is 
3. Transactional Model 
-both sender and receiver are communicators; they are equal 
-communication is simultaneous 
-nonverbal gestures are part of feedback (gestures, body language)</p>
            </div>





             
            <div class="card">
                <h3>Why is Communication Important?</h3>
               <a href="quiz-new.php"> <button>try exercise</button> </a>
            </div>
        </div>
    </div>
</body>
</html>
