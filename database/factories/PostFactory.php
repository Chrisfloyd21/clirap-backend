<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PostFactory extends Factory
{
    public function definition(): array
    {
        $titles = [
            'Grande successo per la cena di beneficenza a Milano',
            'Report della missione sul campo: Marzo 2024',
            'La storia di Amina: come la scuola ha cambiato la sua vita',
            'Emergenza siccità: il nostro intervento immediato',
            'Grazie ai nostri donatori italiani per il supporto',
            'Nuova partnership con le scuole locali in Camerun'
        ];

        $content = "Cari amici e sostenitori, siamo lieti di condividere con voi gli ultimi aggiornamenti dal campo. Grazie alla vostra generosità, siamo riusciti a raggiungere obiettivi straordinari. \n\nLe squadre locali hanno lavorato duramente per garantire che ogni centesimo donato si trasformasse in aiuto concreto. Abbiamo visto sorrisi sui volti dei bambini che hanno ricevuto i loro primi libri e sollievo negli occhi delle madri che ora hanno acqua pulita vicino casa.\n\nContinuate a sostenerci, perché insieme possiamo fare la differenza.";

        $chosenTitle = $this->faker->randomElement($titles);

        return [
            'title_it' => $chosenTitle,
            'slug' => Str::slug($chosenTitle) . '-' . rand(100, 999),
            'content_it' => $content,
            'excerpt_it' => Str::limit($content, 100),
            'image_url' => 'https://source.unsplash.com/random/800x600/?charity,africa',
            'is_published' => true,
        ];
    }
}