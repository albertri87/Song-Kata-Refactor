<?php

namespace codeDojo;

final class Song
{
    public const FIRST_ANIMAL = 0;
    public const ONE_ANIMAL = 1;
    public const ONE_POSITION = 1;

    public const LIRIC_FIRST = 'There was an old lady who swallowed a $animal;';
    public const LIRIC_VARIABLE = [
        'That wriggled and wiggled and tickled inside her.',
        'How absurd to swallow a <$animal>.',
        'Fancy that to swallow a <$animal>!',
        'What a hog, to swallow a <$animal>!',
        'I don\'t know how she swallowed a <$animal>!',
    ];
    public const LIRIC_TO_CATH = 'She swallowed the <$animal> to catch the <$previousAnimal>,';
    public const LIRIC_PENULTIME = 'There was an old lady who swallowed a <$animal>...';
    public const LIRIC_CHORUS = 'I don' . "'" . 't know why she swallowed a <$animal> - perhaps she' . "'" . 'll die!';
    public const LIRIC_LAST =  '...She\'s dead, of course!';

    /**
     * @var array
     */
    private $animals;
    /**
     * @var string;
     */
    private $song = '';


    /**
     * Song constructor.
     * @param array $animals
     */
    public function __construct(array $animals)
    {
        $this->animals = $animals;
    }

    /**
     * @param string $animal
     * @return int
     */
    private function getAnimalPosition(string $animal): int {
        return array_search($animal, $this->animals, true);
    }

    /**
     * @return int
     */
    private function getTotalAnimals(): int{
        return count($this->animals) - self::ONE_ANIMAL;
    }

    public function getSong(): void {
        $this->buildSong();
        echo $this->song;
    }

    private function buildSong(): void{

        $song = '';
        $totalAnimals = $this->getTotalAnimals();

        for($animalsIndx = self::FIRST_ANIMAL; $animalsIndx < $totalAnimals; $animalsIndx++){
            $song.= $this->getFirstLiric($this->animals[$animalsIndx]);
            if($animalsIndx > self::FIRST_ANIMAL) {
                $song.= $this->getVariableLiric($this->animals[$animalsIndx], $animalsIndx);
            }
            $song.= $this->getChorusLiric($animalsIndx);
        }

        $song.= $this->getPenultimeLiric($this->animals[$animalsIndx]);
        $song.= self::LIRIC_LAST;

        $this->song = $song;
    }

    /**
     * @param string $animal
     * @return string
     */
    private function getFirstLiric(string $animal): string {
        $endSentence = self::LIRIC_FIRST . PHP_EOL;
        if($this->getAnimalPosition($animal) === self::FIRST_ANIMAL) {
            $endSentence = str_replace(';', '.', $endSentence);
        }
        return str_replace('$animal', $animal, $endSentence);
    }

    /**
     * @param string $animal
     * @param int $sentenceNum
     * @return string|string[]
     */
    private function getVariableLiric(string $animal, int $sentenceNum) {
        $varLiric = self::LIRIC_VARIABLE[$sentenceNum - self::ONE_POSITION] . PHP_EOL;
        return  str_replace('<$animal>', $animal, $varLiric);
    }

    /**
     * @param string $animal
     * @param string $previousAnimal
     * @return string
     */
    private function getChorusFrase(string $animal, string $previousAnimal): string {
        $sentence = str_replace('<$animal>', $animal, self::LIRIC_TO_CATH);
        $sentence = str_replace('<$previousAnimal>', $previousAnimal, $sentence) . PHP_EOL;
        if($this->getAnimalPosition($previousAnimal) === self::FIRST_ANIMAL) {
            $sentence = str_replace(',', ';', $sentence);
        }
        return  $sentence;
    }

    /**
     * @param $animalsIndx
     * @return string
     */
    private function getChorusLiric($animalsIndx): string {
        $chorus = '';
        for($catchAnimalInd = $animalsIndx; $catchAnimalInd > self::FIRST_ANIMAL; $catchAnimalInd--){
            $chorus.= $this->getChorusFrase($this->animals[$catchAnimalInd],$this->animals[$catchAnimalInd - self::ONE_ANIMAL]);
        }
        return $chorus . $this->getLastChorusFrase($this->animals[self::FIRST_ANIMAL]);
    }

    /**
     * @param string $animal
     * @return string
     */
    private function getPenultimeLiric(string $animal): string {
        return str_replace('<$animal>', $animal, self::LIRIC_PENULTIME) . PHP_EOL;
    }

    /**
     * @param string $animal
     * @return string
     */
    private function getLastChorusFrase(string $animal): string {
        return  str_replace('<$animal>', $animal, self::LIRIC_CHORUS) . PHP_EOL . PHP_EOL;
    }
}