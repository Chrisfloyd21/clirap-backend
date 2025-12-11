<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectFactory extends Factory
{
    public function definition(): array
    {
        // Liste de vrais titres de projets
        $titles = [
            'Costruzione di una scuola primaria a Yaoundé',
            'Progetto Acqua Potabile: 5 nuovi pozzi',
            'Sostegno agricolo per le donne rurali',
            'Kit scolastici per 200 bambini orfani',
            'Campagna di vaccinazione e prevenzione malaria',
            'Ristrutturazione del dispensario medico locale',
            'Borse di studio per studenti meritevoli',
            'Microcredito per cooperative agricole'
        ];

        // Liste de descriptions réalistes
        $descriptions = [
            'Questo progetto mira a fornire un accesso sicuro all\'istruzione per i bambini svantaggiati. Costruiremo tre aule e forniremo banchi e lavagne.',
            'L\'accesso all\'acqua potabile è un diritto umano fondamentale. Il nostro obiettivo è ridurre le malattie legate all\'acqua installando pompe solari.',
            'Sosteniamo l\'imprenditoria femminile attraverso la fornitura di sementi e attrezzature agricole moderne per garantire l\'autosufficienza alimentare.',
            'La salute non può aspettare. Stiamo raccogliendo fondi per inviare medicinali di base e zanzariere alle famiglie nelle zone più remote.'
        ];

        return [
            'title_it' => $this->faker->randomElement($titles),
            'description_it' => $this->faker->randomElement($descriptions),
            'category' => $this->faker->randomElement(['Educazione', 'Salute', 'Sviluppo Rurale', 'Acqua', 'Infanzia']),
            'image_url' => 'https://source.unsplash.com/random/800x600/?africa,people,school', 
            'is_completed' => $this->faker->boolean(20),
        ];
    }
}