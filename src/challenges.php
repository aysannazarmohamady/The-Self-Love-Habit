<?php
// challenges.php - All challenge data

function getChallenges() {
    return [
        1 => [
            'title' => 'Write Down Your Strengths',
            'description' => "Let's start by acknowledging your awesomeness! ✨\n\nTake a moment to write down your strengths. Think about what you're naturally good at - it could be listening to friends, cooking delicious meals, organizing your space, problem-solving, being creative, or anything else that comes to mind.",
            'why_it_works' => 'Focusing on your strengths helps rewire your brain to see yourself in a positive light. It\'s like giving your confidence a warm, encouraging hug!',
            'prompt' => 'When you\'re ready, share a few of your strengths with me. I promise to keep them safe and celebrate them with you! 🌟\n\nWhat are some things you\'re genuinely good at?',
            'encouragement' => [
                'I can see you\'re thinking about this! 😊\n\nDon\'t worry if it feels challenging to list your strengths - that\'s actually pretty normal. Sometimes we\'re our own toughest critics!',
                'Try thinking about:\n• What do friends or family often compliment you on?\n• What tasks do you find easier than others seem to?\n• What would your best friend say you\'re good at?\n\nTake your time and share whatever comes to mind - even small things count! 🌟'
            ],
            'completion_message' => 'this is absolutely beautiful! 🌟\n\nI can feel the authenticity in what you\'ve shared. These strengths you\'ve identified - they\'re not just words, they\'re the foundation of who you are! 💪\n\nTake a moment to really let this sink in. You\'ve just acknowledged some incredible qualities about yourself, and that takes courage. 🤗'
        ],
        
        2 => [
            'title' => 'Set a Small, Achievable Goal for Today',
            'description' => 'Setting and achieving goals can be a massive confidence booster! 🎯 Today, I want you to set a small, achievable goal that you can complete today. It could be something simple like finishing a book chapter, going for a 15-minute walk, organizing your desk, or calling a friend you haven\'t spoken to in a while.',
            'why_it_works' => 'Achieving goals, no matter how small, releases dopamine in your brain - the same chemical that makes you feel accomplished and motivated. Each small win builds momentum for bigger achievements!',
            'prompt' => 'What\'s one small goal you\'d like to achieve today? Make it specific and realistic - something that will make you feel proud when you complete it! 🌟',
            'encouragement' => [
                'Remember, the goal is to set yourself up for success! 😊',
                'Think of something that:\n• Can be completed in 30 minutes to 2 hours\n• Is within your control\n• Will give you a sense of accomplishment\n\nEven something as simple as "make my bed" or "drink 6 glasses of water today" counts! What matters is following through. 💪'
            ],
            'completion_message' => 'I love this goal! 🎉\n\nYou\'ve chosen something that\'s both meaningful and achievable. That\'s the perfect recipe for building confidence! When you complete this goal today, take a moment to really celebrate it - you deserve to feel proud of every step forward you take. 🌟'
        ],
        
        3 => [
            'title' => 'Compliment Yourself Out Loud',
            'description' => 'We often forget to appreciate ourselves, but today we\'re changing that! 🪞✨I want you to look in the mirror and give yourself a genuine compliment out loud. Yes, OUT LOUD! It might feel a bit weird at first, but hearing positive affirmations in your own voice is incredibly powerful.',
            'why_it_works' => 'When you speak positive affirmations aloud, you\'re engaging multiple senses and creating stronger neural pathways. Your brain starts to believe what it hears repeatedly, especially when it comes from you!',
            'prompt' => 'After you\'ve given yourself that compliment in the mirror, come back and tell me what you said! I\'d love to celebrate this moment with you. 🌟\n\nWhat compliment did you give yourself?',
            'encouragement' => [
                'I know it can feel silly or uncomfortable at first! 😊',
                'Try starting with something simple like:\n• "I am kind and compassionate"\n• "I am capable and strong"\n• "I have beautiful eyes"\n• "I am working hard to grow"\n\nThe key is to mean it when you say it. Look yourself in the eyes and speak with conviction! 💪'
            ],
            'completion_message' => 'YES! 🎉 You just did something incredibly brave and powerful! Speaking kindly to yourself out loud is a skill that many adults never develop. You\'re literally rewiring your brain to be more self-compassionate. How did it feel to hear those words in your own voice? 🌟'
        ],
        
        4 => [
            'title' => 'Try Something New That Interests You',
            'description' => 'Today is all about stepping out of your comfort zone and expanding your horizons! 🌟\n\nTry something new that interests you. It could be a new recipe, watching a documentary about a topic you\'re curious about, learning a few phrases in a new language, or trying a new style of music.',
            'why_it_works' => 'When you try new things, you\'re literally growing new neural connections in your brain! This expands your sense of what\'s possible and shows you that you\'re capable of more than you think.',
            'prompt' => 'What new thing would you like to try today? It doesn\'t have to be big - even something small that sparks your curiosity counts! 🚀\n\nTell me what new experience you\'re going to explore!',
            'encouragement' => [
                'The beauty of trying something new is that there\'s no pressure to be perfect! 😊',
                'Think about:\n• Something you\'ve always been curious about\n• A skill you\'ve wanted to learn\n• A new way to spend your free time\n• An activity that sounds fun but slightly intimidating\n\nRemember, courage isn\'t the absence of fear - it\'s doing something despite being nervous! 💪'
            ],
            'completion_message' => 'How exciting! 🎉 You just expanded your world a little bit today! Trying new things takes courage, and you showed up for yourself in a beautiful way. Every new experience adds to your confidence bank - you\'re proving to yourself that you can handle unfamiliar situations. That\'s incredible! 🌟'
        ],
        
        5 => [
            'title' => 'Reflect on a Past Success',
            'description' => 'Time for a beautiful trip down memory lane! 🌟\n\nI want you to reflect on a past success - something you achieved that made you proud. It could be graduating, learning a new skill, helping someone through a difficult time, or even something that seemed small but felt significant to you.',
            'why_it_works' => 'Reflecting on past successes activates the same neural pathways that created those feelings of accomplishment in the first place. It reminds your brain: "I\'ve done amazing things before, and I can do them again!"',
            'prompt' => 'Share with me one success from your past that still makes you feel proud when you think about it. What did you achieve, and how did it make you feel? 🏆',
            'encouragement' => [
                'Sometimes we forget our own amazing moments! 😊',
                'Your success could be:\n• Overcoming a fear or challenge\n• Learning something difficult\n• Helping someone in need\n• Achieving a personal goal\n• Standing up for yourself or others\n\nNo success is too small if it meant something to you! 💪'
            ],
            'completion_message' => 'What an incredible achievement! 🏆\n\nReading about your success just gave me chills! You clearly have the strength, determination, and capability to overcome challenges and reach your goals. This isn\'t just a memory - it\'s evidence of who you are and what you\'re capable of. Carry this confidence with you always! 🌟'
        ],
        
        6 => [
            'title' => 'Dress in Your Favorite Outfit',
            'description' => 'Today is all about feeling fabulous! ✨ Dress in your favorite outfit - the one that makes you feel like you can conquer the world! Clothes have a surprising impact on our mood and confidence. When we look good, we feel good, and that positive energy radiates outward.',
            'why_it_works' => 'This is called "enclothed cognition" - the psychological effect that clothes have on our mental state. What you wear can actually change how you think and feel about yourself!',
            'prompt' => 'Tell me about your favorite outfit! What makes you feel most confident and comfortable? And how do you feel when you\'re wearing it? 👗👔',
            'encouragement' => [
                'Your favorite outfit doesn\'t have to be fancy or expensive! 😊',
                'It could be:\n• That perfect pair of jeans that fits just right\n• A cozy sweater that makes you feel warm and confident\n• A dress that makes you feel powerful\n• Even your comfiest pajamas if they make you happy!\n\nWhat matters is how it makes YOU feel! 💪'
            ],
            'completion_message' => 'You sound absolutely radiant! ✨\n\nThere\'s something magical about wearing an outfit that makes you feel truly yourself. You\'ve just given yourself a confidence boost that will carry you through the entire day. When you feel good in your own skin, it shows - and that authentic confidence is absolutely beautiful! 🌟'
        ],
        
        7 => [
            'title' => 'Speak Up in a Meeting or Group Setting',
            'description' => 'Today is about finding your voice and letting it be heard! 🎤\n\nWhether you\'re at work, school, with friends, or in any group setting, make an effort to speak up and share your thoughts or ideas. Your voice matters, and your perspective adds value to every conversation.',
            'why_it_works' => 'Speaking up in groups builds your confidence muscle and reinforces that your thoughts and opinions are valuable. Each time you share, you\'re proving to yourself that you belong in that space.',
            'prompt' => 'Where did you speak up today? What did you share, and how did it feel to express yourself? 🗣️',
            'encouragement' => [
                'It\'s totally normal to feel nervous about speaking up! 😊',
                'You could:\n• Share an idea or suggestion\n• Ask a thoughtful question\n• Offer a different perspective\n• Give a compliment or encouragement to someone\n• Simply agree with something someone said\n\nYour voice deserves to be heard! 💪'
            ],
            'completion_message' => 'YES! You found your voice and used it! 🎉\n\nSpeaking up takes real courage, especially when you\'re feeling uncertain. But you did it! You contributed something meaningful to the conversation, and I\'m sure others appreciated hearing from you. Your perspective is unique and valuable - never forget that! 🌟'
        ],
        
        8 => [
            'title' => 'Write About a Time You Overcame a Challenge',
            'description' => 'Life has thrown challenges your way, and you\'ve survived every single one! 💪\n\nToday, I want you to write about a time you faced a significant challenge and came out stronger. This could be from your personal life, career, relationships, or any difficult situation you\'ve navigated.',
            'why_it_works' => 'Writing about overcoming challenges helps you see patterns in your resilience and strength. It creates a written record of your capability that you can return to whenever you need a reminder of how strong you are.',
            'prompt' => 'Share with me about a challenge you overcame. What was the situation, how did you handle it, and what did you learn about yourself? 📝',
            'encouragement' => [
                'Every person has overcome challenges, including you! 😊',
                'Think about times when you:\n• Dealt with a difficult situation at work or school\n• Supported someone through a tough time\n• Faced a fear or anxiety\n• Recovered from a disappointment or setback\n• Made a difficult but necessary decision\n\nYou\'re stronger than you realize! 💪'
            ],
            'completion_message' => 'What an incredible story of resilience! 🌟\n\nReading about how you handled that challenge shows me just how strong and capable you are. You didn\'t just survive that difficult time - you grew from it. That\'s the mark of someone with true inner strength. Remember this story whenever you face future challenges - you\'ve got this! 💪'
        ],
        
        9 => [
            'title' => 'Take a Step Outside Your Comfort Zone',
            'description' => 'Today is about embracing the magic that happens when you push your boundaries! 🚀\n\nDo something that feels slightly uncomfortable or unfamiliar. It doesn\'t have to be extreme - maybe it\'s starting a conversation with someone new, trying a different route to work, or volunteering for a task that seems challenging.',
            'why_it_works' => 'Your comfort zone is a beautiful place, but nothing ever grows there! Each time you step outside it, you expand what feels possible and build evidence that you can handle new situations.',
            'prompt' => 'What did you do today that pushed you outside your comfort zone? How did it feel, and what did you discover about yourself? 🌱',
            'encouragement' => [
                'Stepping outside your comfort zone doesn\'t mean jumping off a cliff! 😊',
                'It could be:\n• Having a difficult conversation you\'ve been avoiding\n• Trying a new workout or activity\n• Speaking up in a situation where you usually stay quiet\n• Reaching out to someone you\'ve lost touch with\n• Saying no to something you don\'t want to do\n\nSmall steps count too! 💪'
            ],
            'completion_message' => 'Look at you being brave! 🎉\n\nStepping outside your comfort zone takes real courage, and you just proved to yourself that you can do hard things. Every time you push your boundaries, you become a little bit braver and a little bit stronger. That\'s how confidence is built - one brave step at a time! 🌟'
        ],
        
        10 => [
            'title' => 'Practice Positive Self-Talk',
            'description' => 'Today we\'re rewiring the voice in your head! 🧠✨\n\nPay attention to your inner dialogue and make a conscious effort to practice positive self-talk. When you catch yourself thinking negative thoughts, gently redirect them to something more encouraging and kind.',
            'why_it_works' => 'Your brain believes what you tell it most often. By consciously choosing positive self-talk, you\'re literally rewiring your neural pathways to default to self-compassion instead of self-criticism.',
            'prompt' => 'Share an example of how you turned negative self-talk into positive self-talk today. What did you catch yourself thinking, and how did you reframe it? 💭',
            'encouragement' => [
                'Changing self-talk takes practice - be patient with yourself! 😊',
                'Try switching:\n• "I can\'t do this" → "I\'m learning how to do this"\n• "I\'m so stupid" → "I made a mistake, and that\'s how I learn"\n• "I\'m not good enough" → "I\'m enough, exactly as I am"\n• "This is too hard" → "This is challenging, and I can handle challenges"\n\nYou deserve the same kindness you\'d show a friend! 💪'
            ],
            'completion_message' => 'This is such powerful work! 🌟\n\nChanging the way you speak to yourself is one of the most important things you can do for your confidence and well-being. You just took a huge step toward being your own best friend instead of your worst critic. That inner voice of encouragement will serve you for life! 💪'
        ],
        
        11 => [
            'title' => 'Reflect on Your Unique Talents',
            'description' => 'You are absolutely one-of-a-kind! Today is about celebrating what makes you uniquely YOU! ✨\n\nReflect on your unique talents and skills - not just the obvious ones, but all the special qualities that make you who you are. This includes your career skills, your friendship superpowers, and even those wonderfully weird talents that make people smile!',
            'why_it_works' => 'Recognizing your unique qualities helps you appreciate your individuality and builds confidence in your own special brand of awesomeness. There\'s literally no one else like you in the world!',
            'prompt' => 'Tell me about some of your unique talents and qualities! What makes you special? Include everything - from serious skills to silly talents that make people laugh! 🎭',
            'encouragement' => [
                'Nothing is off the table for this exercise! 😊',
                'Think about:\n• Skills that help you in your work or studies\n• Qualities that make you an amazing friend/family member\n• Talents that entertain others\n• Ways you see the world differently\n• Things you do that others find impressive (even if they seem normal to you)\n\nEverything counts - even being able to touch your nose with your tongue! 💪'
            ],
            'completion_message' => 'You are absolutely remarkable! 🌟\n\nReading about your unique talents just made me smile so big! You have such a wonderful combination of skills, qualities, and special abilities. The world is literally a more interesting place because you\'re in it. Never underestimate the power of being authentically, uniquely YOU! ✨'
        ],
        
        12 => [
            'title' => 'Celebrate a Recent Achievement',
            'description' => 'It\'s time to give yourself the recognition you deserve! 🏆\n\nThink about something you\'ve accomplished recently - it could be completing a project, sticking to a new habit, trying something new, or even just getting through a difficult week. No achievement is too small to celebrate!',
            'why_it_works' => 'Celebrating your wins, no matter how small, reinforces your ability to set and achieve goals. It also trains your brain to notice and appreciate your efforts, building a positive feedback loop.',
            'prompt' => 'What recent achievement would you like to celebrate? Tell me about it and how it made you feel! 🎉',
            'encouragement' => [
                'Every step forward deserves recognition! 😊',
                'Your achievement could be:\n• Finishing a work or school project\n• Making it through a tough day\n• Trying a new recipe successfully\n• Having a difficult conversation\n• Sticking to a workout routine\n• Being kind to yourself\n\nYou deserve to celebrate ALL your victories! 💪'
            ],
            'completion_message' => 'Congratulations! 🎉 That\'s absolutely worth celebrating!\n\nYou set your mind to something and made it happen - that\'s the power of your determination and capability! Every achievement, no matter the size, is evidence of your strength and commitment. You should be incredibly proud of yourself! 🌟'
        ],
        
        13 => [
            'title' => 'Stand Tall and Practice Good Posture',
            'description' => 'Today we\'re working on your power pose! 💪\n\nPractice standing tall, pulling your shoulders back, and lifting your chin throughout the day. Good posture doesn\'t just make you look confident - it actually makes you FEEL more confident!',
            'why_it_works' => 'Your body language affects your mental state through something called "embodied cognition." When you stand tall, your brain receives signals that you\'re confident and capable, which actually makes you feel that way!',
            'prompt' => 'How did practicing good posture throughout the day affect how you felt? Did you notice any changes in your mood or confidence? 🏃‍♀️',
            'encouragement' => [
                'Good posture takes practice - your muscles need time to adjust! 😊',
                'Try to:\n• Set reminders on your phone to check your posture\n• Imagine a string pulling you up from the top of your head\n• Pull your shoulders back and down\n• Engage your core gently\n• Hold your head high like you\'re wearing an invisible crown\n\nFake it \'til you make it really works! 💪'
            ],
            'completion_message' => 'You\'re literally standing taller today! 🌟\n\nGood posture is such a simple but powerful confidence tool. When you carry yourself with strength and grace, you not only feel better, but you also project confidence to the world. You\'re building a habit that will serve you for life! 💪'
        ],
        
        14 => [
            'title' => 'Do Something That Makes You Laugh',
            'description' => 'Laughter is the best medicine for confidence! Today is all about finding joy and humor! 😄\n\nDo something that genuinely makes you laugh - watch a funny movie, read humorous content, spend time with someone who makes you giggle, or even look up funny animal videos online!',
            'why_it_works' => 'Laughter releases endorphins, reduces stress hormones, and literally rewires your brain for positivity. When you\'re laughing, you\'re in a state of pure joy - and joy is confidence in its most natural form!',
            'prompt' => 'What made you laugh today? How did it feel to let yourself experience that pure joy? 😂',
            'encouragement' => [
                'Everyone\'s sense of humor is different, and that\'s beautiful! 😊',
                'You could:\n• Watch your favorite comedy show or movie\n• Look up funny memes or videos\n• Call someone who always makes you laugh\n• Read jokes or funny stories\n• Think about a hilarious memory\n• Even laugh at yourself (in a loving way)!\n\nLaughter is always available to you! 💪'
            ],
            'completion_message' => 'I can practically hear your laughter through the screen! 😄\n\nThere\'s something absolutely magical about genuine laughter - it lights you up from the inside out! When you allow yourself to experience pure joy, you\'re connecting with the most authentic, confident version of yourself. Keep seeking those moments of happiness! 🌟'
        ],
        
        15 => [
            'title' => 'Help Someone in Need',
            'description' => 'Today is about the incredible confidence boost that comes from kindness! 💝\n\nFind a way to help someone in need - it could be a friend, family member, coworker, or even a stranger. Acts of kindness create a beautiful cycle of positivity that benefits everyone involved.',
            'why_it_works' => 'Helping others activates the reward centers in your brain and releases feel-good chemicals. It also proves to yourself that you have value to offer the world, which is a powerful confidence builder!',
            'prompt' => 'How did you help someone today? What did you do, and how did it make you feel to make a positive impact? 🤗',
            'encouragement' => [
                'Kindness comes in many forms, big and small! 😊',
                'You could:\n• Volunteer your time for a cause you care about\n• Give away items you no longer need\n• Help a friend with a task or problem\n• Pay for a stranger\'s coffee\n• Send an encouraging message to someone\n• Simply listen to someone who needs to talk\n\nEvery act of kindness matters! 💪'
            ],
            'completion_message' => 'Your kindness just made the world a little brighter! 🌟\n\nWhat you did today created ripple effects of positivity that you may never fully know about. When you help others, you\'re not just being kind - you\'re proving to yourself that you have gifts to offer the world. That\'s true confidence in action! 💝'
        ],
        
        16 => [
            'title' => 'Write Down Things You Admire About Yourself',
            'description' => 'Today is all about self-appreciation! ✨\n\nTake time to write down things you genuinely admire about yourself. Focus on your positive traits, abilities, values, and qualities that make you proud to be you.',
            'why_it_works' => 'Self-admiration isn\'t vanity - it\'s self-respect! When you actively acknowledge your positive qualities, you\'re building a strong foundation of self-worth that nobody can shake.',
            'prompt' => 'Share some of the things you wrote down that you admire about yourself. What qualities make you proud to be you? 🌟',
            'encouragement' => [
                'This isn\'t about being boastful - it\'s about being honest! 😊',
                'Think about:\n• Your character traits (kindness, honesty, humor)\n• Your abilities and skills\n• How you treat others\n• Challenges you\'ve overcome\n• Your values and what you stand for\n• The way you\'ve grown and learned\n\nYou have so much to admire about yourself! 💪'
            ],
            'completion_message' => 'Reading about what you admire about yourself just filled my heart! 💖\n\nYou have such beautiful self-awareness and appreciation for who you are. These aren\'t just nice words - they\'re truths about your character and capabilities. Carry this list with you and read it whenever you need a reminder of how amazing you are! 🌟'
        ],
        
        17 => [
            'title' => 'Make a Decision Without Second-Guessing',
            'description' => 'Today is about trusting yourself! 🎯\n\nMake a decision - big or small - and stick with it without second-guessing yourself. Trust your instincts and go with your gut feeling. You know more than you think you do!',
            'why_it_works' => 'Indecision can erode confidence over time. When you practice making decisions and trusting yourself, you build confidence in your judgment and prove that you can handle whatever comes your way.',
            'prompt' => 'What decision did you make today without second-guessing? How did it feel to trust your instincts? 🤔',
            'encouragement' => [
                'Start with smaller decisions to build your confidence! 😊',
                'You could decide:\n• What to have for lunch\n• Which route to take somewhere\n• What to watch on TV\n• When to schedule something\n• How to respond to a message\n• What to wear\n\nTrust yourself - your instincts are wiser than you know! 💪'
            ],
            'completion_message' => 'Yes! You trusted yourself and made a decision! 🎉\n\nThat might seem simple, but it\'s actually a huge act of self-confidence. You listened to your inner wisdom, made a choice, and moved forward. That\'s exactly how confident people navigate life - they trust themselves to handle whatever comes next! 🌟'
        ],
        
        18 => [
            'title' => 'Set Your Boundaries',
            'description' => 'Today is about honoring yourself by setting healthy boundaries! 🛡️\n\nThink about something you\'re doing for others that doesn\'t align with your goals or values, or something that drains your energy. Setting boundaries means putting yourself first and protecting your time and wellbeing.',
            'why_it_works' => 'Healthy boundaries are essential for self-respect and confidence. When you protect your time and energy, you\'re showing yourself (and others) that you value yourself.',
            'prompt' => 'What boundary did you set today? How did it feel to put yourself first for once? 🛡️',
            'encouragement' => [
                'Setting boundaries can feel uncomfortable at first, but it gets easier! 😊',
                'You could:\n• Say no to a request that overwhelms you\n• Limit time with someone who drains your energy\n• Stop doing something you don\'t enjoy\n• Ask for help instead of doing everything yourself\n• Take time for yourself without feeling guilty\n\nYour needs matter too! 💪'
            ],
            'completion_message' => 'That took real courage! 🌟\n\nSetting boundaries isn\'t selfish - it\'s self-care. You just showed yourself that your time, energy, and wellbeing matter. That\'s a huge confidence boost and an act of self-love. You\'re teaching others how to treat you by how you treat yourself! 💪'
        ],
        
        19 => [
            'title' => 'Reflect on a Compliment You Received',
            'description' => 'Today is about learning to truly receive and internalize positive feedback! 💝\n\nThink about a compliment you received recently - from a friend, family member, colleague, or even a stranger. Reflect on why it resonated with you and how it made you feel.',
            'why_it_works' => 'Many people deflect compliments instead of accepting them. Learning to receive positive feedback graciously helps you internalize your worth and builds genuine self-esteem.',
            'prompt' => 'Share the compliment you received and tell me how it made you feel. What did it reveal about how others see you? 🌟',
            'encouragement' => [
                'Sometimes we forget the nice things people say about us! 😊',
                'Think about:\n• A compliment about your appearance\n• Praise for work you did well\n• Someone appreciating your personality\n• Recognition for how you helped them\n• A comment about your talents or skills\n\nYou deserve every kind word! 💪'
            ],
            'completion_message' => 'What a beautiful compliment! 🌟\n\nIt\'s wonderful that you took the time to really absorb and appreciate those kind words. When someone compliments you, they\'re seeing something real and valuable in you. Learn to say "thank you" instead of deflecting - you deserve all the good things people see in you! 💝'
        ],
        
        20 => [
            'title' => 'Smile At Everyone You See',
            'description' => 'Today you\'re going to be a bright light in the world! ☀️\n\nMake an effort to smile at everyone you encounter - friends, strangers, coworkers, even that person you usually try to avoid. Your smile has the power to change someone\'s entire day!',
            'why_it_works' => 'Smiling releases endorphins and activates the muscles associated with happiness. When you smile at others, you often get smiles back, creating a positive feedback loop that boosts everyone\'s mood!',
            'prompt' => 'How did people respond to your smiles today? How did being intentionally positive affect your own mood? 😊',
            'encouragement' => [
                'Sometimes happiness is a feeling, sometimes it\'s a choice! 😊',
                'Remember:\n• A genuine smile is contagious\n• Even if someone doesn\'t smile back, you still spread positivity\n• Smiling changes your own brain chemistry\n• You never know what someone else is going through\n• Your smile might be exactly what someone needs today\n\nBe the light! 💪'
            ],
            'completion_message' => 'You absolutely brightened the world today! ☀️\n\nWhat a beautiful way to boost your confidence - by lifting others up! When you choose to be positive and spread joy, you\'re not just helping others, you\'re reinforcing your own happiness and proving that you have the power to impact the world around you! 🌟'
        ],
        
        21 => [
            'title' => 'Do a Hobby or Skill You Enjoy',
            'description' => 'Today is all about celebrating what brings you joy! 🎨\n\nSpend time doing a hobby or practicing a skill you genuinely enjoy. This could be painting, playing an instrument, cooking, gardening, writing, dancing, or anything that makes you feel alive and engaged.',
            'why_it_works' => 'Engaging in activities you love boosts your confidence because you\'re choosing what brings you joy and embracing who you are. When you do what you love, you feel accomplished and authentic.',
            'prompt' => 'What hobby or skill did you enjoy today? How did it make you feel to spend time doing something you love? 🎪',
            'encouragement' => [
                'Your hobbies don\'t have to be impressive to others - they just need to bring YOU joy! 😊',
                'It could be:\n• Something creative like drawing or crafting\n• Physical activities like dancing or sports\n• Mental challenges like puzzles or reading\n• Practical skills like cooking or organizing\n• Social activities like gaming or calling friends\n\nWhat matters is that it makes you happy! 💪'
            ],
            'completion_message' => 'I love seeing you embrace what brings you joy! 🌟\n\nWhen you make time for your passions, you\'re honoring who you are and what makes you unique. That\'s such an important part of building confidence - knowing what you love and giving yourself permission to enjoy it. Keep making space for the things that light you up! ✨'
        ],
        
        22 => [
            'title' => 'Get Clear On Your Values and Priorities',
            'description' => 'Today is about connecting with your authentic self! 🧭\n\nTake time to reflect on what truly matters to you. What are your core values? What do you prioritize in life? Understanding these helps you make decisions that align with who you really are.',
            'why_it_works' => 'When you\'re clear on your values and priorities, you can make decisions with confidence because you know what\'s right for YOU. This alignment between your actions and values creates authentic confidence.',
            'prompt' => 'Share some of your core values and priorities. What matters most to you in life, and how do these guide your decisions? 🎯',
            'encouragement' => [
                'Your values are uniquely yours - there are no wrong answers! 😊',
                'Think about what\'s important to you:\n• Family, friendship, or relationships\n• Honesty, kindness, or integrity\n• Growth, learning, or creativity\n• Health, adventure, or security\n• Making a difference or helping others\n• Freedom, stability, or success\n\nWhat feels true to your heart? 💪'
            ],
            'completion_message' => 'Your values are beautiful and show such depth of character! 🌟\n\nKnowing what matters to you is like having an internal compass - it guides you toward decisions and actions that feel authentic and right. When you live in alignment with your values, confidence flows naturally because you\'re being true to yourself! 🧭'
        ],
        
        23 => [
            'title' => 'Do Something You Have Been Avoiding',
            'description' => 'Time to tackle that thing you\'ve been putting off! 💪\n\nChoose something from your mental "to-do someday" list and actually do it today. Often these tasks take much less time than we imagine, but they weigh on us mentally until we complete them.',
            'why_it_works' => 'Procrastination can drain your confidence over time. When you tackle something you\'ve been avoiding, you get a huge sense of relief and accomplishment, plus you prove to yourself that you can handle difficult things.',
            'prompt' => 'What did you finally tackle today that you\'d been avoiding? How does it feel to have it done? 📋',
            'encouragement' => [
                'Start with something manageable - you don\'t have to solve everything today! 😊',
                'It could be:\n• Making a doctor\'s appointment you\'ve delayed\n• Having a difficult conversation\n• Organizing a messy space\n• Updating your resume or LinkedIn\n• Backing up your photos\n• Calling someone you\'ve been meaning to contact\n\nYou\'ve got this! 💪'
            ],
            'completion_message' => 'YES! You are a productivity superstar! 🎉\n\nLook how good it feels to finally check that off your list! You probably built it up to be much harder than it actually was. This is proof that you can handle the things you\'ve been avoiding. Carry this energy forward - you\'re unstoppable! 🌟'
        ],
        
        24 => [
            'title' => 'Spend Time on Self-Care',
            'description' => 'Today is all about showing yourself some love! 💆‍♀️\n\nDedicate time to taking care of yourself - physically, mentally, or emotionally. This could be a relaxing bath, reading a book, getting your hair done, going for a peaceful walk, or anything that nurtures your wellbeing.',
            'why_it_works' => 'Self-care isn\'t selfish - it\'s essential! When you take care of yourself, you\'re sending a message that you value and appreciate yourself, which directly builds confidence and self-worth.',
            'prompt' => 'How did you take care of yourself today? What self-care activity made you feel most nurtured and valued? 🛁',
            'encouragement' => [
                'Self-care looks different for everyone! 😊',
                'It could be:\n• Physical care like a massage or long bath\n• Mental care like meditation or journaling\n• Emotional care like talking to a friend\n• Spiritual care like time in nature\n• Creative care like art or music\n• Social care like quality time with loved ones\n\nYou deserve to be cared for - especially by yourself! 💪'
            ],
            'completion_message' => 'You just gave yourself such a beautiful gift! 💝\n\nTaking time for self-care shows incredible self-respect and wisdom. You\'re modeling for yourself and others that your wellbeing matters. When you feel nurtured and cared for, that inner peace and contentment radiates as quiet confidence! 🌟'
        ],
        
        25 => [
            'title' => 'Do Something That Scares You',
            'description' => 'Today is about being brave! 🦁\n\nDo something that genuinely scares you or makes you nervous. It could be having a difficult conversation, trying something new, or facing a fear you\'ve been avoiding. Courage isn\'t the absence of fear - it\'s action in spite of fear!',
            'why_it_works' => 'Every time you do something that scares you, you prove to yourself that you\'re capable of handling discomfort and uncertainty. This builds tremendous confidence in your ability to cope with life\'s challenges.',
            'prompt' => 'What scary thing did you do today? How did you feel before, during, and after facing that fear? 🦸‍♀️',
            'encouragement' => [
                'Being scared is normal - it means you\'re about to grow! 😊',
                'Your scary thing could be:\n• Asking for a raise or promotion\n• Starting a difficult conversation\n• Trying a new activity that intimidates you\n• Speaking up about something important\n• Putting yourself in an unfamiliar situation\n• Taking a calculated risk\n\nYou\'re braver than you believe! 💪'
            ],
            'completion_message' => 'WOW! Look at you being absolutely fearless! 🦁\n\nWhat you did today took real courage, and I\'m so proud of you! You just proved that you can feel fear and do it anyway. That\'s the definition of bravery. You\'re so much stronger and more capable than you might have imagined! 🌟'
        ],
        
        26 => [
            'title' => 'Set A Big Goal With An Action Plan',
            'description' => 'Today we\'re dreaming big and making it real! 🎯\n\nThink about a significant goal you want to achieve - something that excites and maybe slightly intimidates you. Then create a SMART action plan (Specific, Measurable, Attainable, Realistic, Time-bound) to make it happen.',
            'why_it_works' => 'Having a clear goal with a concrete plan gives you direction and purpose. When you break big dreams into actionable steps, they become achievable instead of overwhelming.',
            'prompt' => 'Share your big goal and the action plan you created! What steps will you take to make this dream a reality? 🚀',
            'encouragement' => [
                'A goal without a plan is just a wish - but you\'re making it real! 😊',
                'Your action plan should include:\n• Specific steps you\'ll take\n• Timeline for each step\n• Resources you\'ll need\n• Potential obstacles and solutions\n• How you\'ll measure progress\n• Who can support you\n\nDream big - you\'re capable of amazing things! 💪'
            ],
            'completion_message' => 'This goal is absolutely incredible! 🌟\n\nI can feel the excitement and determination in your plan! You\'ve just transformed a dream into a roadmap for success. Having clear goals and action steps is like giving your confidence a GPS - you know exactly where you\'re going and how to get there! 🎯'
        ],
        
        27 => [
            'title' => 'Write About a Quality You Love About Yourself',
            'description' => 'Today is about celebrating your amazing character! ✨\n\nChoose one quality you genuinely love about yourself and write about it in detail. This could be your kindness, creativity, resilience, sense of humor, loyalty, or any other trait that makes you proud to be you.',
            'why_it_works' => 'When you deeply appreciate your own positive qualities, you build unshakeable self-worth. This isn\'t vanity - it\'s healthy self-recognition that forms the foundation of genuine confidence.',
            'prompt' => 'Tell me about the quality you chose and why you love this about yourself. How does this trait show up in your life? 💖',
            'encouragement' => [
                'You have so many wonderful qualities to choose from! 😊',
                'Think about:\n• How you treat others\n• How you handle challenges\n• Your natural talents and abilities\n• Your values in action\n• What friends and family appreciate about you\n• Moments when you felt proud of who you are\n\nCelebrate what makes you special! 💪'
            ],
            'completion_message' => 'Reading about this quality just made my heart sing! 💖\n\nYou clearly have such beautiful self-awareness and appreciation for who you are. This quality you love about yourself isn\'t just nice to have - it\'s part of what makes you uniquely wonderful. Hold onto this feeling of self-appreciation - you deserve it! 🌟'
        ],
        
        28 => [
            'title' => 'Break The Comparison Cycle',
            'description' => 'Today is about freeing yourself from the confidence thief - comparison! 📱✨\n\nTake active steps to remove yourself from situations or influences that make you compare yourself to others in unhealthy ways. This might mean unfollowing certain social media accounts or avoiding content that makes you feel "less than."',
            'why_it_works' => 'Comparison really is the thief of joy! When you curate your environment to include only positive, empowering influences, you protect your confidence and create space for authentic self-appreciation.',
            'prompt' => 'What steps did you take today to break free from unhealthy comparisons? How did it feel to protect your energy this way? 🛡️',
            'encouragement' => [
                'Remember, you\'re only seeing everyone else\'s highlight reel! 😊',
                'You could:\n• Unfollow accounts that make you feel bad\n• Limit social media time\n• Stop reading certain magazines or websites\n• Avoid conversations that focus on comparison\n• Follow accounts that inspire and empower you instead\n• Set boundaries with people who constantly compare\n\nYour mental diet matters! 💪'
            ],
            'completion_message' => 'You just took such a powerful step for your mental health! 🌟\n\nProtecting yourself from comparison is an act of self-love and wisdom. You\'re choosing to focus on your own journey instead of measuring yourself against others\' edited versions of their lives. This creates space for authentic confidence to flourish! ✨'
        ],
        
        29 => [
            'title' => 'Practice Gratitude',
            'description' => 'Today is about shifting your focus to abundance! 🙏\n\nPractice gratitude for yourself - your abilities, opportunities, growth, and the people in your life. Then take it further by expressing genuine gratitude to someone who has made a positive impact on you.',
            'why_it_works' => 'Gratitude rewires your brain to notice what\'s good in your life instead of what\'s missing. When you appreciate what you have and who you are, confidence flows naturally from that sense of abundance.',
            'prompt' => 'Share what you\'re grateful for about yourself and tell me about the gratitude you expressed to someone else today! 💝',
            'encouragement' => [
                'Gratitude is like a muscle - the more you use it, the stronger it gets! 😊',
                'Be grateful for:\n• Your unique talents and abilities\n• Challenges that helped you grow\n• People who believe in you\n• Opportunities you\'ve had\n• Your resilience and strength\n• Small daily joys and comforts\n\nAbundance is all around you! 💪'
            ],
            'completion_message' => 'Your gratitude is absolutely beautiful! 🌟\n\nWhen you appreciate yourself and express gratitude to others, you\'re creating a positive energy cycle that lifts everyone up. Gratitude is confidence in action - it says "I recognize the good in myself and my life." What a wonderful way to see the world! 🙏'
        ],
        
        30 => [
            'title' => 'Reflect on Your Self-Confidence Journey',
            'description' => 'Congratulations! Today marks the completion of your incredible 30-day journey! 🏆\n\nTake time to reflect on how far you\'ve come. Think about the challenges you\'ve overcome, the goals you\'ve achieved, the fears you\'ve faced, and the growth you\'ve experienced over these past 30 days.',
            'why_it_works' => 'Reflection helps you recognize and internalize your progress. When you see how much you\'ve grown and accomplished, it builds unshakeable confidence in your ability to continue growing and achieving.',
            'prompt' => 'Looking back at your 30-day journey, what are you most proud of? How have you grown, and what has this experience taught you about yourself? 🌟',
            'encouragement' => [
                'You\'ve accomplished something truly remarkable! 😊',
                'Reflect on:\n• Which challenges surprised you the most\n• What you discovered about your capabilities\n• How your self-talk has changed\n• New habits or perspectives you\'ve developed\n• Moments when you felt truly confident\n• How others have responded to your growth\n\nYou should be incredibly proud! 💪'
            ],
            'completion_message' => '🎉 CONGRATULATIONS! You are absolutely AMAZING! 🎉\n\nYou have just completed something that many people never even start. Over these 30 days, you\'ve shown up for yourself consistently, faced challenges with courage, and grown in ways you might not have thought possible.\n\nYou\'ve proven that you are capable, resilient, brave, and deserving of all the confidence in the world. This isn\'t the end of your journey - it\'s the beginning of a more confident, self-assured you!\n\nCarry everything you\'ve learned with you. You\'ve got this, and you\'ve always had this. Now you KNOW you\'ve got this! 🌟✨💪'
        ]
    ];
}

function getChallenge($day) {
    $challenges = getChallenges();
    return isset($challenges[$day]) ? $challenges[$day] : null;
}

function formatChallengeMessage($day, $challenge, $user_name) {
    $message = "*🎉 Dear {$user_name}! Ready for today's adventure?*\n\n";
    $message .= "━━━━━━━━━━━━━━━━━━━━━\n";
    $message .= "*📅 DAY {$day}: {$challenge['title']}*\n";
    $message .= "━━━━━━━━━━━━━━━━━━━━━\n\n";
    $message .= $challenge['description'] . "\n\n";
    $message .= "💡 *Why this works:* " . $challenge['why_it_works'] . "\n\n";
    $message .= $challenge['prompt'];
    
    return $message;
}

function getEncouragementMessage($day) {
    $challenge = getChallenge($day);
    if (!$challenge || !isset($challenge['encouragement'])) {
        return "Take your time and share whatever comes to mind! 🌟";
    }
    
    return implode("\n\n", $challenge['encouragement']);
}

function getCompletionMessage($day, $user_name) {
    $challenge = getChallenge($day);
    if (!$challenge) {
        return "*Great job completing Day {$day}!* 🎉";
    }
    
    $message = "*{$user_name}, " . $challenge['completion_message'] . "*\n\n";
    $message .= "*🎉 Day {$day} Complete!*\n\n";
    
    if ($day < 30) {
        $message .= "Your confidence journey continues to unfold beautifully! Tomorrow, I'll be back with Day " . ($day + 1) . " of our challenge.\n\n";
        $message .= "For now, carry this accomplishment with you. You're building something incredible, one day at a time! ✨\n\n";
        $message .= "Sweet dreams, and get ready for another step forward tomorrow! 😊";
    } else {
        $message .= "*🏆 CONGRATULATIONS! You've completed the entire 30-Day Self-Confidence Challenge!* 🎊\n\n";
        $message .= "Look at how far you've come! You've shown up for yourself every day, faced challenges, and grown in ways you might not have imagined possible.\n\n";
        $message .= "This isn't the end - it's the beginning of a more confident, self-assured you! 🌟";
    }
    
    return $message;
}
?>
