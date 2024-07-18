<?php
class Daily
{
    public string $arrivedAt;
    public string $leavedAt;

    public string $workDuration;
    public function __construct($arrivedAt, $leavedAt)
    {
        $this->arrivedAt = $arrivedAt;
        $this->leavedAt = $leavedAt;
    }
    public function calculate()
    {
        $this->workDuration = strtotime($this->leavedAt) - strtotime($this->arrivedAt);
        return gmdate("H:i", $this->workDuration);
    }

    public function Debt()
    {

        $workTime = strtotime('09:00');

        if (date('H:i', $this->workDuration) >= date('H:i', $workTime)) {
            $debitTime = $this->workDuration - $workTime;
            $debt = gmdate('+H:i', $debitTime);
        } elseif (date('H:i', $this->workDuration) < date('H:i', $workTime)) {
            $debitTime = $workTime - $this->workDuration;
            $debt = gmdate('-H:i', $debitTime);
        }
        return $debt;
    }
}
