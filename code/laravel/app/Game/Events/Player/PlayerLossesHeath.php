<?php namespace App\Game\Events\Player;

/**
 * Class PlayerLossesHeath
 * @package App\Game\Events\Player
 */
class PlayerLossesHeath
{

    /**
     * @var
     */
    public $heathLost;

    /**
     * @var
     */
    public $heathLeft;


    /**
     * Handle the event the player losing heath
     * - This is meant to be broadcast and consumed with websockets. Til then, just echo stuff out
     * PlayerLossesHeath constructor.
     *
     * @param $healthLost
     * @param $healthLeft
     */
    public function __construct($healthLost, $healthLeft)
    {
        $this->healthLost   = $healthLost;
        $this->heathLeft    = $healthLeft;

        echo "Player lost $healthLost health; $healthLeft heath remaining.\n";
    }
}