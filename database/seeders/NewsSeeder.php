<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\News;
use Illuminate\Support\Str;
use Carbon\Carbon;

class NewsSeeder extends Seeder
{
    /**
     * Jalankan seeder untuk tabel news.
     */
    public function run(): void
    {
        $newsData = [
            [
                'title' => 'Konser Musik Indramayu Fest 2025 Sukses Guncang Lapangan Wirapati',
                'content' => 'Ribuan penonton memadati Lapangan Wirapati untuk menyaksikan konser musik “Indramayu Fest 2025” yang menghadirkan deretan musisi papan atas seperti Tulus, Pamungkas, dan Fiersa Besari.',
                'author' => 'Admin',
                'image' => 'news/indramayu-fest.jpg',
            ],
            [
                'title' => 'Dewa 19 Gelar Konser Reuni Spesial di Cirebon',
                'content' => 'Konser reuni Dewa 19 di Stadion Bima Cirebon berhasil memukau penonton dengan lagu-lagu legendaris dan penampilan kolaboratif bersama musisi muda.',
                'author' => 'Admin',
                'image' => 'news/dewa19-reuni.jpg',
            ],
            [
                'title' => 'Festival Musik Kampus Polindra 2025 Tampilkan Talenta Lokal',
                'content' => 'Festival musik tahunan Polindra kembali digelar dengan tema “Beat The Future”. Acara ini diikuti oleh berbagai band mahasiswa dari Indramayu dan sekitarnya.',
                'author' => 'Admin',
                'image' => 'news/polindra-festival.jpeg',
            ],
            [
                'title' => 'Konser Amal “Sound of Hope” Kumpulkan Dana untuk Pendidikan Anak Desa',
                'content' => 'Konser bertajuk “Sound of Hope” berhasil mengumpulkan dana sebesar 120 juta rupiah untuk membantu pendidikan anak-anak kurang mampu di pedesaan.',
                'author' => 'Admin',
                'image' => 'news/sound-of-hope.jpg',
            ],
            [
                'title' => 'Pamungkas Siap Hibur Penggemar di Tur “Golden Way 2025”',
                'content' => 'Setelah sukses dengan album terbarunya, Pamungkas kembali menggelar tur nasional “Golden Way 2025” dengan jadwal konser di 12 kota besar Indonesia.',
                'author' => 'Admin',
                'image' => 'news/pamungkas-goldenway.jpg',
            ],
            [
                'title' => 'Konser Jazz di Tengah Sawah Jadi Daya Tarik Wisata Baru di Cirebon',
                'content' => 'Konsep unik konser jazz di area persawahan Cirebon menarik perhatian wisatawan lokal dan mancanegara. Acara ini menggabungkan musik dan nuansa alam pedesaan.',
                'author' => 'Admin',
                'image' => 'news/jazz-sawah.jpg',
            ],
            [
                'title' => 'Polindra Gelar Konser Tribute to Sheila on 7',
                'content' => 'Dalam rangka dies natalis kampus, Polindra menghadirkan konser “Tribute to Sheila on 7” yang dibawakan oleh band mahasiswa dengan aransemen modern.',
                'author' => 'Admin',
                'image' => 'news/tribute-so7.jpg',
            ],
            [
                'title' => 'Konser “Rock in Harmony” Gaungkan Pesan Damai Lewat Musik',
                'content' => 'Konser rock lintas genre ini membawa pesan perdamaian dan persatuan antar generasi melalui lagu-lagu berenergi tinggi.',
                'author' => 'Admin',
                'image' => 'news/rock-in-harmony.jpg',
            ],
            [
                'title' => 'Festival Musik Jalanan Cirebon 2025 Meriahkan Malam Minggu',
                'content' => 'Musisi jalanan dari berbagai daerah tampil di area Alun-alun Kejaksan dalam acara Festival Musik Jalanan 2025 yang disambut antusias warga.',
                'author' => 'Admin',
                'image' => 'news/festival-jalanan.jpg',
            ],
            [
                'title' => 'Konser Virtual “Melody of Future” Hadirkan Teknologi AR',
                'content' => 'Konser virtual pertama di Cirebon menggunakan teknologi Augmented Reality (AR) membuat penonton dapat menikmati pengalaman musik interaktif dari rumah.',
                'author' => 'Admin',
                'image' => 'news/melody-of-future.jpg',
            ],
        ];

        foreach ($newsData as $item) {
            News::create([
                'title' => $item['title'],
                'slug' => Str::slug($item['title']) . '-' . uniqid(),
                'content' => $item['content'],
                // simpan nama file relatif ke storage/app/public
                'image' => $item['image'],
                'author' => $item['author'],
                'published_at' => Carbon::now()->subDays(rand(1, 10)),
            ]);
        }
    }
}
