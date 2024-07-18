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
//        $boshlanish_vaqti = new DateTime('08:00');
//        $tugash_vaqti = new DateTime('17:00');
//        $vaqt1 = new DateTime($this->arrivedAt);
//        $vaqt2 = new DateTime($this->leavedAt);
//
//        $oraliq_vaqt1 = $boshlanish_vaqti -> diff($vaqt1);
//        $oraliq_vaqt2 = $vaqt2 -> diff($tugash_vaqti);
//
//        $soat1 = $oraliq_vaqt1->h;
//        $minut1 = $oraliq_vaqt1->i;
//        $soat2 = $oraliq_vaqt2->h;
//        $minut2 = $oraliq_vaqt2->i;
//        $a1 = "$soat1:$minut1";
//        $b1 = "$soat2:$minut2";
//        list($soatt1,$minutt1)=explode(":",$a1);
//        $a1SOAT = $soatt1 * 60 + $minutt1;
//        list($soatt2,$minutt2)=explode(":",$b1);
//        $b1SOAT = $soatt2 * 60 + $minutt2;
//        $javob = $a1SOAT + $b1SOAT;
//        $javobSOAT = floor($javob / 60);
//        $javobMINUT = $javob % 60;
//        $soatMinut = sprintf("%02d:%02d",$javobSOAT,$javobMINUT);

//        $this->workDuration = strtotime($this->workDuration);
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
