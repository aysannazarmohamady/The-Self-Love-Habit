<?php
// Rewritten 30-Day Self-Confidence Challenges
// More natural, conversational English for native speakers

function getChallenges() {
    return [
        1 => [
            'title' => 'Write Down Your Strengths',
            'description' => "Let's start simple. Take a few minutes to write down what you're actually good at. Could be anything - listening well, making people laugh, staying organized, cooking, problem-solving. Whatever comes to mind.",
            'why_it_works' => 'When you focus on your strengths instead of what you think you lack, your brain starts noticing more of what you do well. It\'s like training yourself to see the good stuff.',
            'prompt' => 'Share a few of your strengths with me. What are you genuinely good at?',
            'encouragement' => [
                'I know this can feel uncomfortable. Most of us are way better at listing our flaws than our strengths.',
                'Think about it this way:\n• What do people compliment you on?\n• What feels easy to you that others struggle with?\n• What would your best friend say you\'re good at?\n\nEven small things count.'
            ],
            'completion_message' => 'this is great! These aren\'t just random things - they\'re real parts of who you are. Take a second to let that sink in. You just acknowledged something good about yourself, and that actually takes courage.'
        ],
        
        2 => [
            'title' => 'Set a Small, Achievable Goal for Today',
            'description' => 'Pick one small thing you can actually finish today. Not "change your life" - just something doable. Read a chapter, go for a walk, clean your desk, call someone you\'ve been meaning to text.',
            'why_it_works' => 'Finishing things - even tiny things - gives you a hit of dopamine. That\'s the chemical that makes you feel accomplished. Small wins build momentum.',
            'prompt' => 'What\'s one thing you want to get done today? Make it specific and realistic.',
            'encouragement' => [
                'The key here is setting yourself up to actually succeed.',
                'Pick something that:\n• Takes 30 minutes to 2 hours max\n• You actually have control over\n• Will make you feel good when it\'s done\n\nEven "make my bed" counts. Just follow through.'
            ],
            'completion_message' => 'Perfect. That\'s doable and meaningful. When you finish it today, take a second to actually acknowledge it. You deserve to feel good about following through.'
        ],
        
        3 => [
            'title' => 'Compliment Yourself Out Loud',
            'description' => 'Here\'s the weird one. Look in the mirror and say something nice to yourself. Out loud. Yes, really. It\'ll probably feel awkward, but that\'s kind of the point.',
            'why_it_works' => 'When you hear positive things in your own voice, it hits different than just thinking them. Your brain actually starts believing what it hears repeatedly.',
            'prompt' => 'After you do it, come back and tell me what you said.',
            'encouragement' => [
                'Yeah, it feels ridiculous at first. Do it anyway.',
                'Try something simple:\n• "I\'m doing my best"\n• "I\'m capable"\n• "I have kind eyes"\n• "I\'m working on growing"\n\nLook yourself in the eye and mean it.'
            ],
            'completion_message' => 'You actually did it! That\'s honestly brave. Most people never do this. How did it feel to hear those words in your own voice?'
        ],
        
        4 => [
            'title' => 'Try Something New',
            'description' => 'Do something you haven\'t done before. Doesn\'t have to be big - try a new recipe, watch a documentary on something random, learn a few words in another language, listen to a different genre of music.',
            'why_it_works' => 'New experiences literally create new neural pathways in your brain. You\'re proving to yourself that you can handle unfamiliar situations.',
            'prompt' => 'What new thing are you going to try today?',
            'encouragement' => [
                'The point isn\'t to be good at it - just to try it.',
                'Think about:\n• Something you\'ve been curious about\n• A skill you\'ve wanted to learn\n• Something that sounds fun but slightly intimidating\n\nYou don\'t have to master it. Just show up.'
            ],
            'completion_message' => 'Nice! You just expanded your world a little bit. Every new experience proves you can handle things you haven\'t done before. That adds up.'
        ],
        
        5 => [
            'title' => 'Reflect on a Past Success',
            'description' => 'Think about something you accomplished that made you proud. Could be graduating, learning something hard, helping someone through tough times, or anything that felt significant to you.',
            'why_it_works' => 'Remembering past wins reminds your brain: "I\'ve done hard things before. I can do them again."',
            'prompt' => 'Tell me about a success from your past that still makes you feel good. What did you achieve and how did it feel?',
            'encouragement' => [
                'We forget our own wins way too easily.',
                'Your success could be:\n• Overcoming something difficult\n• Learning a challenging skill\n• Helping someone who needed it\n• Achieving a personal goal\n• Standing up for yourself\n\nIf it meant something to you, it counts.'
            ],
            'completion_message' => 'That\'s a real achievement. You clearly have the capability to overcome challenges and reach goals. This isn\'t just a memory - it\'s proof of what you can do.'
        ],
        
        6 => [
            'title' => 'Wear Your Favorite Outfit',
            'description' => 'Wear whatever makes you feel good today. The outfit that makes you feel like you\'ve got your shit together. Clothes affect mood more than you\'d think.',
            'why_it_works' => 'There\'s actual science behind this - it\'s called "enclothed cognition." What you wear changes how you think and feel. When you look good, you genuinely feel more confident.',
            'prompt' => 'What\'s your go-to outfit that makes you feel most like yourself? How do you feel when you\'re wearing it?',
            'encouragement' => [
                'Doesn\'t have to be fancy or expensive.',
                'Could be:\n• That perfect pair of jeans\n• A hoodie that just feels right\n• A dress that makes you feel powerful\n• Even your favorite pajamas\n\nWhatever makes YOU feel good.'
            ],
            'completion_message' => 'There\'s something about wearing the right outfit that just hits different. When you feel good in what you\'re wearing, it shows. That confidence is real.'
        ],
        
        7 => [
            'title' => 'Speak Up in a Group',
            'description' => 'Whether it\'s at work, school, or with friends - say something. Share a thought, ask a question, contribute to the conversation. Your voice deserves space.',
            'why_it_works' => 'Each time you speak up, you\'re proving to yourself that your thoughts are worth sharing. It gets easier the more you do it.',
            'prompt' => 'Where did you speak up today? What did you say and how did it feel?',
            'encouragement' => [
                'Feeling nervous is completely normal.',
                'You could:\n• Share an idea\n• Ask a question\n• Offer a different perspective\n• Simply agree with someone\n• Give someone a compliment\n\nAnything counts.'
            ],
            'completion_message' => 'You did it. You used your voice. That takes courage, especially when you\'re not sure how it\'ll be received. Your perspective matters - don\'t forget that.'
        ],
        
        8 => [
            'title' => 'Write About Overcoming a Challenge',
            'description' => 'Think about a time you faced something difficult and got through it. Write about what happened, how you handled it, and what you learned.',
            'why_it_works' => 'Writing about challenges you\'ve overcome creates a record of your resilience. When things get hard again, you\'ll have proof that you\'ve done hard things before.',
            'prompt' => 'Tell me about a challenge you overcame. What was it, how did you handle it, and what did you learn about yourself?',
            'encouragement' => [
                'Everyone has overcome something.',
                'Could be:\n• A difficult situation at work or school\n• Supporting someone through hard times\n• Facing a fear\n• Recovering from a setback\n• Making a tough decision\n\nYou\'re tougher than you think.'
            ],
            'completion_message' => 'That\'s a powerful story. You didn\'t just survive that - you grew from it. That\'s real strength. Remember this when you face new challenges.'
        ],
        
        9 => [
            'title' => 'Step Outside Your Comfort Zone',
            'description' => 'Do something that feels slightly uncomfortable. Start a conversation with someone new, take a different route, volunteer for something that seems challenging. Doesn\'t have to be huge.',
            'why_it_works' => 'Your comfort zone is safe, but nothing grows there. Each time you push the edge a little, you prove you can handle unfamiliar situations.',
            'prompt' => 'What did you do today that pushed you outside your comfort zone? How did it feel?',
            'encouragement' => [
                'Stepping outside your comfort zone doesn\'t mean doing something terrifying.',
                'Could be:\n• Having a conversation you\'ve been avoiding\n• Trying a new activity\n• Speaking up when you usually stay quiet\n• Reaching out to someone\n• Saying no to something\n\nSmall steps count.'
            ],
            'completion_message' => 'You did something that felt uncomfortable and you survived it. Actually, you did more than survive - you proved you can handle new situations. That\'s growth.'
        ],
        
        10 => [
            'title' => 'Practice Positive Self-Talk',
            'description' => 'Pay attention to how you talk to yourself today. When you catch yourself being harsh or critical, try reframing it into something kinder.',
            'why_it_works' => 'Your brain believes what you tell it most often. When you practice kinder self-talk, you\'re literally rewiring your default internal dialogue.',
            'prompt' => 'Share an example of how you reframed negative self-talk today. What did you catch yourself thinking, and how did you change it?',
            'encouragement' => [
                'This takes practice. Be patient with yourself.',
                'Try switching:\n• "I can\'t do this" → "I\'m still learning"\n• "I\'m so stupid" → "I made a mistake - that\'s how I learn"\n• "I\'m not good enough" → "I\'m doing my best"\n• "This is too hard" → "This is challenging, but I can try"\n\nTalk to yourself like you\'d talk to a friend.'
            ],
            'completion_message' => 'This is actually hard work. Changing how you talk to yourself is one of the most important things you can do. You just took a real step toward being your own ally instead of your own critic.'
        ],
        
        11 => [
            'title' => 'Reflect on Your Unique Talents',
            'description' => 'Think about what makes you different. Your skills, quirks, perspectives, weird talents - everything that makes you uniquely you.',
            'why_it_works' => 'When you recognize your unique qualities, you start appreciating your individuality instead of trying to be like everyone else.',
            'prompt' => 'What makes you unique? Include everything - serious skills and silly talents.',
            'encouragement' => [
                'Nothing is too small or too weird.',
                'Could be:\n• Work or study skills\n• What makes you a good friend\n• Talents that make people laugh\n• How you see things differently\n• Things you do that others find impressive\n\nAll of it counts.'
            ],
            'completion_message' => 'You have a really interesting mix of qualities and talents. The world is literally more interesting because you\'re in it. That\'s not nothing.'
        ],
        
        12 => [
            'title' => 'Celebrate a Recent Achievement',
            'description' => 'Think about something you accomplished recently. Could be finishing a project, sticking to a new habit, trying something new, or just getting through a hard week.',
            'why_it_works' => 'Celebrating wins - even small ones - trains your brain to notice your progress instead of only seeing what\'s left to do.',
            'prompt' => 'What recent achievement do you want to celebrate? Tell me about it.',
            'encouragement' => [
                'Every step forward counts.',
                'Could be:\n• Finishing a project\n• Making it through a tough day\n• Trying something new\n• Having a difficult conversation\n• Sticking to a goal\n• Being kind to yourself\n\nAll wins deserve recognition.'
            ],
            'completion_message' => 'That\'s worth celebrating. You set out to do something and you did it. That\'s evidence of your capability. Be proud of that.'
        ],
        
        13 => [
            'title' => 'Practice Good Posture',
            'description' => 'Stand tall today. Pull your shoulders back, lift your chin, stand like you mean it. Your posture affects how you feel more than you\'d think.',
            'why_it_works' => 'Your body language actually affects your mental state. When you stand tall, your brain gets the signal that you\'re confident - and you start feeling that way.',
            'prompt' => 'How did focusing on your posture affect how you felt today?',
            'encouragement' => [
                'Good posture takes practice.',
                'Try to:\n• Set phone reminders\n• Imagine a string pulling you up\n• Pull shoulders back and down\n• Hold your head like you\'re wearing a crown\n\nFake it till you make it actually works.'
            ],
            'completion_message' => 'You literally stood taller today. When you carry yourself with confidence, you feel it and others see it. Simple but effective.'
        ],
        
        14 => [
            'title' => 'Do Something That Makes You Laugh',
            'description' => 'Watch something funny, hang out with someone who makes you laugh, look up dumb videos - whatever actually makes you laugh out loud.',
            'why_it_works' => 'Laughter releases endorphins and reduces stress. When you\'re genuinely laughing, you\'re in a state of pure joy - and that\'s confidence in its most natural form.',
            'prompt' => 'What made you laugh today? How did it feel?',
            'encouragement' => [
                'Everyone\'s sense of humor is different.',
                'Could be:\n• Your favorite comedy\n• Funny videos or memes\n• Someone who always makes you laugh\n• Silly memories\n• Even laughing at yourself\n\nLaughter is always available.'
            ],
            'completion_message' => 'Genuine laughter is magic. When you let yourself experience pure joy, you\'re connecting with the most authentic version of yourself. Keep finding those moments.'
        ],
        
        15 => [
            'title' => 'Help Someone',
            'description' => 'Find a way to help someone today. Could be a friend, family member, coworker, or stranger. Doesn\'t have to be huge.',
            'why_it_works' => 'Helping others activates your brain\'s reward centers and proves you have value to offer. That builds confidence from the inside out.',
            'prompt' => 'How did you help someone today? What did you do and how did it feel?',
            'encouragement' => [
                'Kindness comes in all sizes.',
                'Could be:\n• Volunteering your time\n• Helping with a task\n• Listening to someone\n• Buying someone coffee\n• Sending an encouraging message\n• Being there for someone\n\nEvery bit helps.'
            ],
            'completion_message' => 'You made someone\'s day better. That creates ripple effects you might never see. When you help others, you prove to yourself that you matter.'
        ],
        
        16 => [
            'title' => 'Write What You Admire About Yourself',
            'description' => 'Write down things you genuinely admire about yourself. Your positive traits, abilities, values, qualities that make you who you are.',
            'why_it_works' => 'Self-admiration isn\'t arrogance - it\'s self-respect. When you acknowledge your positive qualities, you build self-worth that nobody can shake.',
            'prompt' => 'What do you admire about yourself? What makes you proud to be you?',
            'encouragement' => [
                'This isn\'t bragging - it\'s being honest.',
                'Think about:\n• Your character (kindness, honesty, humor)\n• Your skills and abilities\n• How you treat people\n• Challenges you\'ve overcome\n• What you stand for\n• How you\'ve grown\n\nYou have plenty to admire.'
            ],
            'completion_message' => 'These aren\'t just nice words - they\'re truths about who you are. Keep this list. Read it when you need reminding of how capable you are.'
        ],
        
        17 => [
            'title' => 'Make a Decision Without Second-Guessing',
            'description' => 'Make a decision today - doesn\'t matter how big - and stick with it. Trust your gut and don\'t overthink it.',
            'why_it_works' => 'Constant second-guessing erodes confidence. When you practice trusting yourself, you build confidence in your own judgment.',
            'prompt' => 'What decision did you make without second-guessing? How did it feel to just trust yourself?',
            'encouragement' => [
                'Start small if you need to.',
                'Could be:\n• What to eat\n• Which route to take\n• What to watch\n• When to schedule something\n• How to respond to a message\n• What to wear\n\nTrust yourself - you know more than you think.'
            ],
            'completion_message' => 'You trusted yourself and made a choice. That might seem simple, but it\'s actually a big deal. Confident people don\'t agonize over everything - they trust themselves to handle whatever happens.'
        ],
        
        18 => [
            'title' => 'Set a Boundary',
            'description' => 'Say no to something that doesn\'t serve you. Set a boundary around your time, energy, or wellbeing. Put yourself first for once.',
            'why_it_works' => 'Healthy boundaries are essential for self-respect. When you protect your time and energy, you show yourself (and others) that you matter.',
            'prompt' => 'What boundary did you set today? How did it feel to prioritize yourself?',
            'encouragement' => [
                'Setting boundaries feels uncomfortable at first.',
                'Could be:\n• Saying no to a request\n• Limiting time with someone draining\n• Stopping something you don\'t enjoy\n• Asking for help\n• Taking time for yourself guilt-free\n\nYour needs matter too.'
            ],
            'completion_message' => 'That took courage. Boundaries aren\'t selfish - they\'re self-care. You just showed yourself that your time and energy are valuable. That\'s powerful.'
        ],
        
        19 => [
            'title' => 'Reflect on a Compliment',
            'description' => 'Think about a compliment you received recently. Really sit with it. Why did it resonate? What does it reveal about how others see you?',
            'why_it_works' => 'Most people deflect compliments instead of accepting them. Learning to actually receive positive feedback helps you internalize your worth.',
            'prompt' => 'Share the compliment you received and how it made you feel. What did it reveal about how others see you?',
            'encouragement' => [
                'We forget the good things people say.',
                'Could be:\n• About your appearance\n• Praise for your work\n• Someone appreciating your personality\n• Recognition for helping them\n• A comment about your talents\n\nYou deserve those words.'
            ],
            'completion_message' => 'When someone compliments you, they\'re seeing something real. Learn to just say "thank you" instead of brushing it off. You deserve the good things people see in you.'
        ],
        
        20 => [
            'title' => 'Smile at Everyone',
            'description' => 'Make an effort to smile at everyone you see today. Friends, strangers, coworkers, that person you usually avoid. Be deliberately positive.',
            'why_it_works' => 'Smiling releases endorphins and activates happiness muscles. When you smile at others, you often get smiles back - creating a positive loop.',
            'prompt' => 'How did people respond to your smiles? How did it affect your own mood?',
            'encouragement' => [
                'Sometimes happiness is a choice.',
                'Remember:\n• Genuine smiles are contagious\n• Even if someone doesn\'t smile back, you still spread positivity\n• Smiling changes your brain chemistry\n• You never know what someone\'s going through\n• Your smile might be what someone needs\n\nBe that person.'
            ],
            'completion_message' => 'You brightened the world today. When you choose to be positive, you\'re not just helping others - you\'re proving that you can impact the energy around you.'
        ],
        
        21 => [
            'title' => 'Do Your Favorite Hobby',
            'description' => 'Spend time doing something you genuinely enjoy. Paint, play music, cook, garden, write, dance - whatever lights you up.',
            'why_it_works' => 'When you do what you love, you feel accomplished and authentic. You\'re choosing joy and embracing who you are.',
            'prompt' => 'What did you do today that you love? How did it make you feel?',
            'encouragement' => [
                'Your hobbies don\'t need to impress anyone.',
                'Could be:\n• Something creative\n• Physical activity\n• Mental challenges\n• Practical skills\n• Social activities\n\nWhat matters is that it makes YOU happy.'
            ],
            'completion_message' => 'When you make time for what you love, you\'re honoring yourself. That\'s an important part of confidence - knowing what brings you joy and actually doing it.'
        ],
        
        22 => [
            'title' => 'Get Clear on Your Values',
            'description' => 'Think about what actually matters to you. What are your core values? What do you prioritize? Understanding this helps you make decisions that feel right.',
            'why_it_works' => 'When you\'re clear on your values, you can make decisions confidently because you know what\'s right for you. That alignment creates authentic confidence.',
            'prompt' => 'What are your core values? What matters most to you and how does that guide your choices?',
            'encouragement' => [
                'Your values are yours - no wrong answers.',
                'Could be:\n• Family, friendship, relationships\n• Honesty, kindness, integrity\n• Growth, learning, creativity\n• Health, adventure, security\n• Making a difference\n• Freedom, stability, success\n\nWhat feels true?'
            ],
            'completion_message' => 'Knowing what matters to you is like having an internal compass. When you live aligned with your values, confidence comes naturally because you\'re being authentic.'
        ],
        
        23 => [
            'title' => 'Do Something You\'ve Been Avoiding',
            'description' => 'Tackle something from your mental "I should do that someday" list. These tasks often take less time than we think, but weigh on us mentally.',
            'why_it_works' => 'Procrastination drains confidence. When you finally do something you\'ve been avoiding, you get relief AND proof that you can handle hard things.',
            'prompt' => 'What did you finally do that you\'d been putting off? How does it feel to have it done?',
            'encouragement' => [
                'Pick something manageable.',
                'Could be:\n• Making an appointment\n• Having a difficult conversation\n• Organizing a messy space\n• Updating your resume\n• Backing up photos\n• Calling someone\n\nYou can do this.'
            ],
            'completion_message' => 'Look at that - done! You probably built it up to be harder than it actually was. This proves you can handle the things you\'ve been avoiding. Carry that forward.'
        ],
        
        24 => [
            'title' => 'Practice Self-Care',
            'description' => 'Take care of yourself today. Could be a bath, reading, getting your hair done, going for a walk - anything that nurtures you.',
            'why_it_works' => 'Self-care isn\'t selfish - it\'s essential. When you take care of yourself, you\'re saying you value yourself. That builds self-worth.',
            'prompt' => 'How did you take care of yourself today? What made you feel most nurtured?',
            'encouragement' => [
                'Self-care looks different for everyone.',
                'Could be:\n• Physical care (massage, bath)\n• Mental care (meditation, journaling)\n• Emotional care (talking to a friend)\n• Spiritual care (time in nature)\n• Creative care (art, music)\n• Social care (quality time)\n\nYou deserve care - especially from yourself.'
            ],
            'completion_message' => 'You just gave yourself something valuable. Taking time for self-care shows self-respect. When you feel nurtured, that inner peace shows as quiet confidence.'
        ],
        
        25 => [
            'title' => 'Do Something That Scares You',
            'description' => 'Do something that genuinely makes you nervous. Have that difficult conversation, try that new thing, face that fear. Courage isn\'t the absence of fear - it\'s action despite fear.',
            'why_it_works' => 'Every time you do something scary, you prove you can handle discomfort. This builds massive confidence in your ability to cope with challenges.',
            'prompt' => 'What scary thing did you do? How did you feel before, during, and after?',
            'encouragement' => [
                'Being scared means you\'re about to grow.',
                'Could be:\n• Asking for a raise\n• Starting a difficult conversation\n• Trying something intimidating\n• Speaking up about something important\n• Putting yourself out there\n• Taking a calculated risk\n\nYou\'re braver than you think.'
            ],
            'completion_message' => 'You did something that scared you and you survived it. Actually, you did more than survive. You just proved you can feel fear and do it anyway. That\'s real courage.'
        ],
        
        26 => [
            'title' => 'Set a Big Goal with a Plan',
            'description' => 'Think about a significant goal - something that excites you and maybe intimidates you a little. Then make it real with a concrete action plan.',
            'why_it_works' => 'A clear goal with a solid plan gives you direction. When you break big dreams into steps, they become achievable instead of overwhelming.',
            'prompt' => 'What\'s your big goal and what\'s your plan to get there?',
            'encouragement' => [
                'A goal without a plan is just a wish.',
                'Your plan should include:\n• Specific steps\n• Timeline for each step\n• Resources you need\n• Potential obstacles\n• How you\'ll measure progress\n• Who can support you\n\nMake it real.'
            ],
            'completion_message' => 'You just transformed a dream into a roadmap. Having clear goals and steps is like giving yourself GPS - you know where you\'re going and how to get there.'
        ],
        
        27 => [
            'title' => 'Write About a Quality You Love',
            'description' => 'Pick one quality you genuinely love about yourself and write about it. Your kindness, creativity, resilience, humor, loyalty - whatever makes you proud.',
            'why_it_works' => 'When you deeply appreciate your own qualities, you build unshakeable self-worth. This isn\'t vanity - it\'s healthy self-recognition.',
            'prompt' => 'Tell me about the quality you chose and why you love this about yourself. How does it show up in your life?',
            'encouragement' => [
                'You have so many good qualities.',
                'Think about:\n• How you treat people\n• How you handle challenges\n• Your natural talents\n• Your values in action\n• What others appreciate about you\n• Times you felt proud of yourself\n\nCelebrate what makes you good.'
            ],
            'completion_message' => 'This quality isn\'t just nice to have - it\'s part of what makes you who you are. Hold onto this feeling. You deserve to appreciate yourself.'
        ],
        
        28 => [
            'title' => 'Break the Comparison Cycle',
            'description' => 'Remove yourself from situations that make you compare yourself to others. Unfollow accounts that make you feel "less than." Protect your mental space.',
            'why_it_works' => 'Comparison really does steal joy. When you curate your environment to include only positive influences, you create space for real confidence.',
            'prompt' => 'What did you do to stop comparing yourself to others? How did it feel to protect your energy?',
            'encouragement' => [
                'You\'re only seeing everyone\'s highlight reel.',
                'You could:\n• Unfollow draining accounts\n• Limit social media time\n• Avoid triggering content\n• Stop participating in comparison conversations\n• Follow inspiring people instead\n• Set boundaries\n\nYour mental diet matters.'
            ],
            'completion_message' => 'You just took a powerful step for your mental health. Protecting yourself from comparison creates space for authentic confidence. You\'re choosing your own journey over everyone else\'s edited versions.'
        ],
        
        29 => [
            'title' => 'Practice Gratitude',
            'description' => 'Focus on what you\'re grateful for - about yourself, your abilities, opportunities, the people in your life. Then express genuine gratitude to someone who matters.',
            'why_it_works' => 'Gratitude rewires your brain to notice what\'s good instead of what\'s missing. When you appreciate what you have, confidence flows naturally.',
            'prompt' => 'What are you grateful for about yourself? And who did you express gratitude to today?',
            'encouragement' => [
                'Gratitude is like a muscle - it gets stronger with practice.',
                'Be grateful for:\n• Your unique talents\n• Challenges that helped you grow\n• People who believe in you\n• Opportunities you\'ve had\n• Your resilience\n• Small daily joys\n\nAbundance is everywhere.'
            ],
            'completion_message' => 'When you appreciate yourself and express gratitude to others, you create positive energy. Gratitude is confidence in action - recognizing the good in yourself and your life.'
        ],
        
        30 => [
            'title' => 'Reflect on Your Journey',
            'description' => 'You made it. 30 days. Take time to reflect on how far you\'ve come. The challenges you faced, the goals you achieved, the fears you conquered, the growth you experienced.',
            'why_it_works' => 'Reflection helps you recognize and internalize progress. When you see how much you\'ve grown, it builds unshakeable confidence in your ability to keep growing.',
            'prompt' => 'Looking back at these 30 days, what are you most proud of? How have you grown and what has this taught you about yourself?',
            'encouragement' => [
                'You actually did this.',
                'Reflect on:\n• Which challenges surprised you\n• What you discovered about yourself\n• How your self-talk has changed\n• New habits you\'ve developed\n• Moments you felt truly confident\n• How others have responded to your growth\n\nBe proud.'
            ],
            'completion_message' => 'You finished what most people never start. Over 30 days, you showed up for yourself consistently. You faced challenges, pushed your boundaries, and grew in real ways.\n\nYou proved you\'re capable, resilient, and brave. This isn\'t the end - it\'s the beginning of a more confident you.\n\nCarry what you learned forward. You\'ve always had this in you. Now you know it.'
        ]
    ];
}

function getChallenge($day) {
    $challenges = getChallenges();
    return isset($challenges[$day]) ? $challenges[$day] : null;
}

function getEncouragementMessage($day) {
    $challenge = getChallenge($day);
    if (!$challenge || !isset($challenge['encouragement'])) {
        return "Take your time. Share whatever comes to mind.";
    }
    
    return implode("\n\n", $challenge['encouragement']);
}

function getCompletionMessage($day, $user_name) {
    $challenge = getChallenge($day);
    if (!$challenge) {
        return "*Great job completing Day {$day}!*";
    }
    
    $message = "*{$user_name}, " . $challenge['completion_message'] . "*\n\n";
    $message .= "*Day {$day} Complete.*\n\n";
    
    if ($day < 30) {
        $message .= "Tomorrow brings Day " . ($day + 1) . ". For now, carry this forward. You're building something real, one day at a time.\n\n";
        $message .= "See you tomorrow.";
    } else {
        $message .= "*You completed all 30 days.*\n\n";
        $message .= "Look how far you came. You showed up every day, faced challenges, and grew.\n\n";
        $message .= "This isn't the end - it's the beginning of a more confident you.";
    }
    
    return $message;
}
?>
