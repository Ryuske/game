<?php namespace App\Game\Combat;

use Event;
use App\Game\Combat\Enemies\Map;
use App\Game\Events\Combat\EnemyFound;
use App\Game\Formulas\Combat\Combat as CombatFormulas;
use App\Game\Formulas\Generic as GenericFormulas;
use App\Game\Player\Player;

/**
 * Class CombatScenario
 * @package App\Game\Combat
 */
class CombatScenario
{

    /**
     * $combat = new CombatScenario;
     * $combat->lookForEnemy();
     * $combat->player()->attack() | $combat->player()->run()
     * $combat->enemy()->attack() | $combat->enemy()->run()
     *
     * You're now fighting <enemy>
     * -- random, who goes first --
     * <enemy> attacks
     * <enemy> does X damage | <enemy> missed
     * Player attacks
     * Player does X damage | Player missed
     * ...
     * Player runs
     * Player successfully ran away | Player failed to run away
     */

    /**
     * @var Player
     */
    public $player;

    /**
     * @var
     */
    public $enemy;

    /**
     * @var Map
     */
    protected $enemiesMap;

    /**
     * @var GenericFormulas
     */
    protected $genericFormulas;

    /**
     * @var CombatFormulas
     */
    protected $combatFormulas;

    /**
     * CombatScenario constructor.
     */
    public function __construct(Player &$player)
    {
        $this->player           = $player;
        $this->enemiesMap       = new Map;
        $this->genericFormulas  = new GenericFormulas;
        $this->combatFormulas   = new CombatFormulas;
    }

    /**
     * Look for an enemy to fight
     * - You have a 75% chance of finding an enemy, otherwise nothing is found
     *
     * @return bool|void
     */
    public function lookForEnemy()
    {
        $isEnemyFound = $this->genericFormulas->probabilityCalculator(75);

        if (!$isEnemyFound) {
            return false;
        }

        $this->enemy = $this->combatFormulas->findEnemyIn($this->player->location()->city);

        Event::fire(new EnemyFound($this->enemy));

        $this->beginCombat();

        return true;
    }


    /**
     * If an enemy is found, begin the combat
     *
     * @return void
     */
    public function beginCombat() {
        $playerStarts = $this->combatFormulas->DoesPlayerGetFirstMove();

        if (!$playerStarts) {
            $this->enemy()->attack();
        }
    }

    /**
     * Accessor to player combat actions
     *
     * @return Fighter\Player
     */
    public function player()
    {
        return new Fighter\Player($this);
    }

    /**
     * Accessor to enemy combat actions
     *
     * @return Fighter\Enemy
     */
    public function enemy()
    {
        return new Fighter\Enemy($this);
    }
}