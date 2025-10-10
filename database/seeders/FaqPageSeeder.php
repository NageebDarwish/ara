<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Page;
use App\Models\FaqSection;
use App\Models\FaqItem;

class FaqPageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create or update FAQ page
        $faqPage = Page::updateOrCreate(
            ['slug' => 'faq'],
            [
                'name' => 'FAQ',
                'title' => 'Frequently Asked Questions',
                'description' => 'Find answers to the most common questions about our Arabic learning platform.',
            ]
        );

        // Delete existing sections to refresh
        FaqSection::where('page_id', $faqPage->id)->delete();

        // Section 1: About Arabic All The Time
        $section1 = FaqSection::create([
            'page_id' => $faqPage->id,
            'title' => 'About Arabic All The Time',
            'description' => '',
            'order' => 0,
        ]);

        FaqItem::create([
            'faq_section_id' => $section1->id,
            'question' => 'What is Arabic All The Time?',
            'answer' => 'Arabic All The Time is an immersive language acquisition platform where you acquire Arabic naturally, just like you learned your first language. By watching a library of engaging, context-rich videos, you absorb Arabic intuitively—without studying grammar rules, memorizing vocabulary lists, or completing traditional exercises.',
            'order' => 0,
        ]);

        FaqItem::create([
            'faq_section_id' => $section1->id,
            'question' => 'What is comprehensible input?',
            'answer' => 'Comprehensible input is language that is slightly above your current level of understanding but still clear enough to grasp through context. It\'s the foundation of natural language acquisition, allowing you to absorb new vocabulary, grammar, and expressions intuitively—without conscious effort. As you engage with comprehensible input over time, you\'ll notice your understanding deepening and your ability to use the language improving naturally, just as you did with your first language.',
            'order' => 1,
        ]);

        FaqItem::create([
            'faq_section_id' => $section1->id,
            'question' => 'How much does Arabic All The Time cost?',
            'answer' => "Arabic All The Time offers both Free and Premium memberships:\n\nFree Account: Includes access to a curated selection of videos, progress tracking for milestones and input hours, the ability to save favorite videos for easy access, and access to community forums to connect with other acquirers and guides.\n\nPremium Membership:\n- Monthly Plan: $14.99/month, which you can cancel anytime.\n- Yearly Plan: $119.88/year (equivalent to $9.99/month), saving you 33.3%.\n\nPremium members enjoy everything in Free plus unlimited access to our video library, two exclusive new videos added daily, offline downloads, and advanced progress tracking features.",
            'order' => 2,
        ]);

        FaqItem::create([
            'faq_section_id' => $section1->id,
            'question' => 'What\'s included in the Premium subscription?',
            'answer' => "Premium membership provides access to everything in Free in addition to:\n- Our entire video library, covering all levels of Arabic acquisition.\n- Two exclusive new videos added daily.\n- Offline downloads for learning anywhere, anytime.\n- Advanced progress tracking features.",
            'order' => 3,
        ]);

        FaqItem::create([
            'faq_section_id' => $section1->id,
            'question' => 'Can I cancel my Premium subscription anytime?',
            'answer' => 'Yes, you can cancel your Premium membership at any time. After cancellation, you\'ll retain access to Premium features until the end of your current billing cycle. To cancel, simply log in to your account, go to the "Account Settings" section, and follow the prompts. If you need assistance, our support team is always here to help!',
            'order' => 4,
        ]);

        FaqItem::create([
            'faq_section_id' => $section1->id,
            'question' => 'Where can I watch?',
            'answer' => 'You can enjoy Arabic All The Time on any internet-connected device, including your computer, smartphone, or tablet. Premium users can also download videos for offline access, so you can learn wherever you are.',
            'order' => 5,
        ]);

        FaqItem::create([
            'faq_section_id' => $section1->id,
            'question' => 'What can I watch on Arabic All The Time?',
            'answer' => 'Our video library caters to all levels, from Super Beginner to Advanced. Discover content ranging from slow, beginner-friendly videos to advanced stories rich in cultural insights and real-life scenarios. The diversity of topics ensures you acquire Arabic in an intuitive, enjoyable way.',
            'order' => 6,
        ]);

        FaqItem::create([
            'faq_section_id' => $section1->id,
            'question' => 'Is Arabic All The Time suitable for complete beginners with no prior knowledge of Arabic?',
            'answer' => 'Absolutely! Arabic All The Time is designed to be accessible for users with no prior exposure to Arabic. Our Super Beginner videos use slow, clear speech and plenty of visual context to help you start understanding the language from day one. You don\'t need any background knowledge to begin—just press play and let the natural acquisition process guide you.',
            'order' => 7,
        ]);

        FaqItem::create([
            'faq_section_id' => $section1->id,
            'question' => 'How does Arabic All The Time compare to other Arabic learning platforms?',
            'answer' => 'Arabic All The Time stands out by focusing on comprehensible input, a proven method for natural language acquisition. Unlike traditional platforms that emphasize grammar drills or vocabulary memorization, we offer engaging, context-rich videos that help you absorb Arabic intuitively. Think of it as learning a language the way you learned your first—by listening, understanding, and enjoying. Our unique approach combines the effectiveness of comprehensible input with cultural immersion, making language acquisition seamless and enjoyable.',
            'order' => 8,
        ]);

        FaqItem::create([
            'faq_section_id' => $section1->id,
            'question' => 'Is Arabic All The Time good for kids?',
            'answer' => 'Yes! While most of our content is designed for adults, children can also benefit from our immersive approach. The natural, intuitive method mirrors how children learn languages, making it suitable for younger audiences, provided the content aligns with their interests.',
            'order' => 9,
        ]);

        // Section 2: Understanding Arabic: language and culture
        $section2 = FaqSection::create([
            'page_id' => $faqPage->id,
            'title' => 'Understanding Arabic: language and culture',
            'description' => '',
            'order' => 1,
        ]);

        FaqItem::create([
            'faq_section_id' => $section2->id,
            'question' => 'What\'s the difference between Modern Standard Arabic and Arabic dialects?',
            'answer' => 'Modern Standard Arabic (MSA) is the formal version of Arabic used in media, literature, and official settings. It serves as a universal foundation for communication across the Arab world. Dialects are regional variations spoken in daily life, with unique vocabulary, pronunciation, and grammar. All dialects share core similarities with MSA, making it an excellent starting point for Arabic Learners.',
            'order' => 0,
        ]);

        FaqItem::create([
            'faq_section_id' => $section2->id,
            'question' => 'Will I learn Modern Standard Arabic or a dialect?',
            'answer' => 'Our primary focus is on Modern Standard Arabic (MSA), as it is widely understood across the Arab world and serves as a versatile foundation for communication. With MSA, you\'ll be able to connect with nearly half a billion speakers, understand most media, and navigate professional settings. While learning a dialect can be useful for specific regions, MSA provides the best starting point.',
            'order' => 1,
        ]);

        FaqItem::create([
            'faq_section_id' => $section2->id,
            'question' => 'Will I learn cultural aspects of the Arab world on this platform?',
            'answer' => 'Absolutely! Arabic All The Time integrates cultural insights into every video, highlighting everyday life, traditions, and nuances from across the Arab world. Understanding culture is an essential part of language acquisition, and our content ensures you gain both linguistic and cultural fluency.',
            'order' => 2,
        ]);

        FaqItem::create([
            'faq_section_id' => $section2->id,
            'question' => 'Can I use Arabic All The Time without knowing the Arabic alphabet?',
            'answer' => 'Yes, you can! Arabic All The Time is designed to help you acquire Arabic through listening and understanding spoken language. Learning the alphabet can be helpful later, but it\'s not necessary to start your journey with comprehensible input. Focus on listening and building comprehension first, and the alphabet will feel easier to learn when you\'re ready. Check our Approach page for more details.',
            'order' => 3,
        ]);

        // Section 3: Learning approach and methodology
        $section3 = FaqSection::create([
            'page_id' => $faqPage->id,
            'title' => 'Learning approach and methodology',
            'description' => '',
            'order' => 2,
        ]);

        FaqItem::create([
            'faq_section_id' => $section3->id,
            'question' => 'How does Arabic All The Time work?',
            'answer' => 'Arabic All The Time uses the comprehensible input approach, allowing you to absorb the language naturally by watching engaging, context-rich videos. With consistent exposure, you acquire vocabulary, grammar, and pronunciation intuitively—without studying rules or memorizing lists.',
            'order' => 0,
        ]);

        FaqItem::create([
            'faq_section_id' => $section3->id,
            'question' => 'How long will it take me to become fluent?',
            'answer' => "Fluency depends on the number of hours of comprehensible input you accumulate:\n- 20 Hours: You'll start feeling more comfortable with the language.\n- 200 Hours: You'll follow focused, slow-paced content with ease.\n- 1,000 Hours: You'll understand most native media and can start speaking.\n\nConsistency and dedication are key—daily exposure accelerates progress.",
            'order' => 1,
        ]);

        FaqItem::create([
            'faq_section_id' => $section3->id,
            'question' => 'Do I need to study grammar?',
            'answer' => 'No, studying grammar isn\'t necessary and can sometimes even be harmful. Just as children acquire their first language, you\'ll absorb Arabic grammar naturally through exposure. Over time, you\'ll start using correct grammar intuitively, without needing to consciously study rules.',
            'order' => 2,
        ]);

        FaqItem::create([
            'faq_section_id' => $section3->id,
            'question' => 'Will I need to memorize vocabulary?',
            'answer' => 'No, vocabulary sticks naturally through repeated exposure in different contexts. By watching videos consistently, words and phrases will become part of your understanding without the need for memorization.',
            'order' => 3,
        ]);

        FaqItem::create([
            'faq_section_id' => $section3->id,
            'question' => 'Do I need to take notes while watching videos?',
            'answer' => 'No, you should avoid taking notes as it can disrupt your listening. Simply enjoy the videos and focus on understanding the overall message. Trust the process—your brain will absorb the language naturally.',
            'order' => 4,
        ]);

        FaqItem::create([
            'faq_section_id' => $section3->id,
            'question' => 'Can watching with subtitles help, or should I avoid them?',
            'answer' => 'Avoid subtitles in your native language, as they can distract from listening to Arabic. However, Arabic subtitles can be helpful for advanced acquirers looking to improve their recognition of written Arabic. For beginners and intermediate acquirers, relying on visuals and context is more effective for natural language absorption.',
            'order' => 5,
        ]);

        FaqItem::create([
            'faq_section_id' => $section3->id,
            'question' => 'What should I do if I don\'t understand everything in a video?',
            'answer' => 'It\'s normal not to understand every word. Focus on the overall meaning, as your brain will naturally fill in the gaps over time. Gradual exposure to context-rich content ensures effective language acquisition.',
            'order' => 6,
        ]);

        FaqItem::create([
            'faq_section_id' => $section3->id,
            'question' => 'Can I listen to Arabic All The Time videos passively in the background?',
            'answer' => 'Active listening is the most effective way to acquire Arabic, especially in the early stages. Focused attention helps your brain connect meaning to words and phrases, which is essential for language acquisition. While passive listening can familiarize you with the rhythm and sounds of Arabic, real progress comes from active listening and comprehension.',
            'order' => 7,
        ]);

        FaqItem::create([
            'faq_section_id' => $section3->id,
            'question' => 'When and how will I start speaking Arabic?',
            'answer' => 'Speaking will emerge naturally after absorbing enough comprehensible input. This occurs at different milestones in your journey, so celebrate it when it happens, but don\'t force it too early. We recommend focusing on listening and comprehension until you\'ve received approximately 1,000 hours of input. Before that, you may naturally begin to say words or phrases—feel free to use them, but avoid pressuring yourself to practice speaking prematurely. Forcing speech too soon can lead to borrowing the speaking patterns of your native language, which will lead you to have unclear pronunciation. Trust the process, and you\'ll intuitively form sentences based on the speaking patterns and structures you\'ve absorbed.',
            'order' => 8,
        ]);

        FaqItem::create([
            'faq_section_id' => $section3->id,
            'question' => 'Should I practice reading too?',
            'answer' => 'Reading is a valuable immersion tool but is most effective after you\'ve built a strong foundation of listening and comprehension through comprehensible input. Starting to read too early may cause your brain to borrow incorrect pronunciation patterns from your native language. Focus first on absorbing the sounds and rhythm of Arabic. Once you\'re comfortable, add reading and writing if they align with your personal goals or interests.',
            'order' => 9,
        ]);

        // Section 4: Practical Questions for Learning Arabic
        $section4 = FaqSection::create([
            'page_id' => $faqPage->id,
            'title' => 'Practical Questions for Learning Arabic',
            'description' => '',
            'order' => 3,
        ]);

        FaqItem::create([
            'faq_section_id' => $section4->id,
            'question' => 'Can Arabic All The Time prepare me for travel in Arabic-speaking countries?',
            'answer' => 'Yes! The language and cultural insights you gain from our videos will help you navigate Arabic-speaking regions confidently. You\'ll understand basic conversations, ask questions, and feel more at ease during your travels.',
            'order' => 0,
        ]);

        FaqItem::create([
            'faq_section_id' => $section4->id,
            'question' => 'How can I use Arabic All The Time to learn specific topics or vocabulary?',
            'answer' => 'Our content is categorized by topics such as travel, culture, and daily life, allowing you to focus on specific areas of interest. For example, if you\'re preparing for a trip, explore videos on conversational phrases or cultural customs. As you watch, you\'ll naturally pick up vocabulary and expressions related to your chosen themes without deliberate memorization.',
            'order' => 1,
        ]);

        FaqItem::create([
            'faq_section_id' => $section4->id,
            'question' => 'How will I know if I\'m making progress?',
            'answer' => 'Progress happens gradually and may feel subtle at first. You\'ll start recognizing familiar words and phrases more frequently, following longer conversations, and grasping complex ideas with less effort. Over time, Arabic will feel more intuitive, and you\'ll find yourself understanding content that once seemed challenging. Check our Approach page for more details.',
            'order' => 2,
        ]);

        FaqItem::create([
            'faq_section_id' => $section4->id,
            'question' => 'How should I track my progress?',
            'answer' => 'The best way to measure progress is by tracking the hours of comprehensible input you\'ve received—not by counting days, months, or years. Focus on consistent exposure to meaningful, context-rich content. The more input you receive, the faster you\'ll progress.',
            'order' => 3,
        ]);

        FaqItem::create([
            'faq_section_id' => $section4->id,
            'question' => 'Can I use Arabic All The Time with other methods or formal courses?',
            'answer' => 'Comprehensible input is the easiest, fastest, and most effective way to achieve lasting fluency in Arabic. We recommend that you focus entirely on receiving comprehensible input through videos, podcasts, audiobooks, reading, or crosstalk. When it comes to language learning, deliberate practice can lead to some irreversible faulty habits.',
            'order' => 4,
        ]);

        // Section 5: Advanced language learning
        $section5 = FaqSection::create([
            'page_id' => $faqPage->id,
            'title' => 'Advanced language learning',
            'description' => '',
            'order' => 4,
        ]);

        FaqItem::create([
            'faq_section_id' => $section5->id,
            'question' => 'I already know some Arabic. Will this method be too basic for me?',
            'answer' => 'Not at all! Arabic All The Time offers content for all levels, from Super Beginner to Advanced. Even if you have a background in Arabic, comprehensible input can power your language acquisition and help you achieve a lasting fluency faster.',
            'order' => 0,
        ]);

        FaqItem::create([
            'faq_section_id' => $section5->id,
            'question' => 'How will I know if my listening skills are improving?',
            'answer' => 'You\'ll notice progress as you begin understanding longer sentences, following conversations more easily, and recognizing words and phrases with less effort. Over time, listening comprehension will feel more intuitive, and Arabic will start to "click."',
            'order' => 1,
        ]);

        FaqItem::create([
            'faq_section_id' => $section5->id,
            'question' => 'Will I eventually reach native-level fluency with this method?',
            'answer' => 'Absolutely! With consistent exposure, you will achieve lasting fluency. Check our Approach page for more details.',
            'order' => 2,
        ]);

        FaqItem::create([
            'faq_section_id' => $section5->id,
            'question' => 'What should I do once I feel fluent?',
            'answer' => 'Continue engaging with comprehensible input and look for opportunities to immerse yourself further. Conversations with native speakers, Arabic media, and real-life experiences will deepen your skills. Language acquisition is a lifelong journey, and ongoing exposure ensures continuous growth.',
            'order' => 3,
        ]);

        // Section 6: Community and feedback
        $section6 = FaqSection::create([
            'page_id' => $faqPage->id,
            'title' => 'Community and feedback',
            'description' => '',
            'order' => 5,
        ]);

        FaqItem::create([
            'faq_section_id' => $section6->id,
            'question' => 'Can I suggest video topics or content ideas?',
            'answer' => 'Absolutely! We love hearing from our Community and welcome your ideas. You can share feedback or suggest topics through the comments section or our Contact page. Your input helps us create content that meets your needs and enriches the platform.',
            'order' => 0,
        ]);

        FaqItem::create([
            'faq_section_id' => $section6->id,
            'question' => 'What should I do if I start losing motivation?',
            'answer' => 'It\'s natural for motivation to ebb and flow. Try setting small, manageable goals, like watching one video daily or tracking your input hours to see your progress. Remind yourself that language acquisition is a journey—every minute of exposure brings you closer to fluency. Celebrate small milestones to keep your momentum going and make the process enjoyable.',
            'order' => 1,
        ]);

        FaqItem::create([
            'faq_section_id' => $section6->id,
            'question' => 'What should I do if I feel frustrated or overwhelmed?',
            'answer' => 'Feeling frustrated is a normal part of language acquisition, especially when challenging yourself. If you feel overwhelmed, take a step back and return to videos that are easier to understand. Progress is gradual, and every video enhances your comprehension over time. Trust the process, celebrate small wins, enjoy the journey, and before you know it, you will be fluent in Arabic.',
            'order' => 2,
        ]);

        $this->command->info('FAQ page seeded with all sections and items successfully!');
    }
}
