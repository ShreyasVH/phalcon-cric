<?php


namespace app\models;


use app\requests\matches\BowlingFigureRequest;

class BowlingFigure extends BaseModel
{
    public $id;
    public $match_player_id;
    public $balls;
    public $maidens;
    public $runs;
    public $wickets;
    public $innings;

    public function initialize()
    {
        $this->setSource('bowling_figures');
    }

    public static function with_bowling_figure_request(BowlingFigureRequest $bowling_figure_request, $player_to_match_player_map)
    {
        $bowling_figure = new self();

        $bowling_figure->match_player_id = $player_to_match_player_map[$bowling_figure_request->playerId];
        $bowling_figure->balls = $bowling_figure_request->balls;
        $bowling_figure->maidens = $bowling_figure_request->maidens;
        $bowling_figure->runs = $bowling_figure_request->runs;
        $bowling_figure->wickets = $bowling_figure_request->wickets;
        $bowling_figure->innings = $bowling_figure_request->innings;

        return $bowling_figure;
    }

    public static function add(array $bowling_figure_requests, $player_to_match_player_map)
    {
        $bowling_figures = [];

        /** @var BowlingFigureRequest $bowling_figure_request */
        foreach($bowling_figure_requests as $bowling_figure_request)
        {
            $bowling_figure = BowlingFigure::with_bowling_figure_request($bowling_figure_request, $player_to_match_player_map);
            $bowling_figure->save();
            $bowling_figures[] = $bowling_figure;
        }

        return $bowling_figures;
    }

    /**
     * @param int[] $match_player_ids
     * @return BowlingFigure[]
     */
    public static function get_by_match_player_ids(array $match_player_ids): array
    {
        return self::toList(self::find([
            'conditions' => 'match_player_id IN ({matchPlayerIds:array})',
            'bind' => ['matchPlayerIds' => $match_player_ids]
        ]));
    }
}