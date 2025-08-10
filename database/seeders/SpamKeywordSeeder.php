<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SpamKeyword;

class SpamKeywordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $spamKeywords = [
            // Money/Finance related
            ['keyword' => 'make money fast', 'weight' => 40, 'category' => 'financial', 'description' => 'Common financial spam phrase'],
            ['keyword' => 'earn \$\d+', 'weight' => 35, 'category' => 'financial', 'description' => 'Money earning claims', 'is_regex' => true],
            ['keyword' => 'get rich quick', 'weight' => 40, 'category' => 'financial', 'description' => 'Get rich quick schemes'],
            ['keyword' => 'work from home', 'weight' => 25, 'category' => 'financial', 'description' => 'Work from home offers'],
            ['keyword' => 'guaranteed income', 'weight' => 35, 'category' => 'financial', 'description' => 'Income guarantees'],
            ['keyword' => 'financial freedom', 'weight' => 20, 'category' => 'financial', 'description' => 'Financial freedom claims'],
            
            // Promotional/Sales
            ['keyword' => 'buy now', 'weight' => 30, 'category' => 'promotional', 'description' => 'Urgent buying pressure'],
            ['keyword' => 'click here', 'weight' => 25, 'category' => 'promotional', 'description' => 'Click bait'],
            ['keyword' => 'limited time', 'weight' => 25, 'category' => 'promotional', 'description' => 'Time pressure tactics'],
            ['keyword' => 'act now', 'weight' => 30, 'category' => 'promotional', 'description' => 'Urgency pressure'],
            ['keyword' => 'free trial', 'weight' => 20, 'category' => 'promotional', 'description' => 'Free trial offers'],
            ['keyword' => 'no obligation', 'weight' => 20, 'category' => 'promotional', 'description' => 'No obligation claims'],
            ['keyword' => 'special offer', 'weight' => 15, 'category' => 'promotional', 'description' => 'Special offers'],
            
            // Cryptocurrency/Investment
            ['keyword' => 'bitcoin investment', 'weight' => 35, 'category' => 'crypto', 'description' => 'Bitcoin investment spam'],
            ['keyword' => 'cryptocurrency trading', 'weight' => 30, 'category' => 'crypto', 'description' => 'Crypto trading spam'],
            ['keyword' => 'forex trading', 'weight' => 30, 'category' => 'crypto', 'description' => 'Forex trading spam'],
            ['keyword' => 'binary options', 'weight' => 35, 'category' => 'crypto', 'description' => 'Binary options spam'],
            ['keyword' => 'mining profit', 'weight' => 30, 'category' => 'crypto', 'description' => 'Mining profit claims'],
            
            // Adult/Inappropriate
            ['keyword' => 'xxx', 'weight' => 50, 'category' => 'adult', 'description' => 'Adult content'],
            ['keyword' => 'porn', 'weight' => 50, 'category' => 'adult', 'description' => 'Pornographic content'],
            ['keyword' => 'escort', 'weight' => 45, 'category' => 'adult', 'description' => 'Escort services'],
            ['keyword' => 'dating site', 'weight' => 30, 'category' => 'adult', 'description' => 'Dating site promotion'],
            ['keyword' => 'sexy', 'weight' => 25, 'category' => 'adult', 'description' => 'Sexually suggestive content'],
            
            // Gambling
            ['keyword' => 'online casino', 'weight' => 40, 'category' => 'gambling', 'description' => 'Online casino promotion'],
            ['keyword' => 'slot machine', 'weight' => 35, 'category' => 'gambling', 'description' => 'Slot machine promotion'],
            ['keyword' => 'poker online', 'weight' => 30, 'category' => 'gambling', 'description' => 'Online poker promotion'],
            ['keyword' => 'betting tips', 'weight' => 25, 'category' => 'gambling', 'description' => 'Betting promotion'],
            
            // Pharmaceutical/Health
            ['keyword' => 'viagra', 'weight' => 45, 'category' => 'pharmaceutical', 'description' => 'Viagra spam'],
            ['keyword' => 'cialis', 'weight' => 45, 'category' => 'pharmaceutical', 'description' => 'Cialis spam'],
            ['keyword' => 'weight loss', 'weight' => 20, 'category' => 'pharmaceutical', 'description' => 'Weight loss products'],
            ['keyword' => 'miracle cure', 'weight' => 35, 'category' => 'pharmaceutical', 'description' => 'Miracle cure claims'],
            ['keyword' => 'lose weight fast', 'weight' => 25, 'category' => 'pharmaceutical', 'description' => 'Fast weight loss claims'],
            
            // Contact/Communication
            ['keyword' => 'whatsapp.*\+?\d{10,}', 'weight' => 35, 'category' => 'contact', 'description' => 'WhatsApp with phone number', 'is_regex' => true],
            ['keyword' => 'telegram.*@\w+', 'weight' => 30, 'category' => 'contact', 'description' => 'Telegram contact info', 'is_regex' => true],
            ['keyword' => 'call.*\+?\d{10,}', 'weight' => 30, 'category' => 'contact', 'description' => 'Phone number solicitation', 'is_regex' => true],
            ['keyword' => 'email.*@.*\.com', 'weight' => 20, 'category' => 'contact', 'description' => 'Email address in comment', 'is_regex' => true],
            
            // Indonesian spam terms
            ['keyword' => 'cari duit', 'weight' => 30, 'category' => 'financial', 'description' => 'Indonesian: looking for money'],
            ['keyword' => 'bisnis online', 'weight' => 25, 'category' => 'promotional', 'description' => 'Indonesian: online business'],
            ['keyword' => 'judi online', 'weight' => 45, 'category' => 'gambling', 'description' => 'Indonesian: online gambling'],
            ['keyword' => 'slot gacor', 'weight' => 40, 'category' => 'gambling', 'description' => 'Indonesian: winning slots'],
            ['keyword' => 'togel', 'weight' => 40, 'category' => 'gambling', 'description' => 'Indonesian lottery gambling'],
            ['keyword' => 'obat kuat', 'weight' => 40, 'category' => 'pharmaceutical', 'description' => 'Indonesian: strong medicine'],
            
            // Suspicious patterns
            ['keyword' => 'https?:\/\/[^\s]+.*https?:\/\/[^\s]+', 'weight' => 35, 'category' => 'suspicious', 'description' => 'Multiple URLs', 'is_regex' => true],
            ['keyword' => '[A-Z]{10,}', 'weight' => 20, 'category' => 'suspicious', 'description' => 'Excessive capital letters', 'is_regex' => true],
            ['keyword' => '!{5,}', 'weight' => 15, 'category' => 'suspicious', 'description' => 'Excessive exclamation marks', 'is_regex' => true],
        ];

        foreach ($spamKeywords as $keyword) {
            SpamKeyword::create([
                'keyword' => $keyword['keyword'],
                'weight' => $keyword['weight'],
                'category' => $keyword['category'],
                'description' => $keyword['description'],
                'is_regex' => $keyword['is_regex'] ?? false,
                'is_active' => true,
            ]);
        }
    }
}
