<nav class="pagination-wrapper text-left">
    <ul class="pagination">
        <li><span>总数:<?php echo $this->total;?></span></li>
        <?php if($total_length):?>
        <li><a href="<?php echo $this->url . '1';?>">首页</a></li>
        <?php endif;?>
        <?php if($this->current > 1) :?>
        <li>
            <a href="<?php echo $this->url . ($numbers[0] - 1) ;?>">
                <span><i class="icon-double-angle-left"></i></span>
            </a>
        </li>
        <?php endif;?>
        <?php foreach($numbers as $number):?>
            <li
                <?php if($this->current == $number): ?>
                    class="active"
                <?php endif ;?>
                ><a href="<?php echo $this->url . $number ;?>"><?php echo $number ;?></a></li>
        <?php endforeach;?>
        <?php if($this->current < $total_length):?>
        <li>
            <a href="<?php echo $this->url . ($this->current + 1);?>">
                <span><i class="icon-double-angle-right"></i></span>
            </a>
        </li>
        <?php endif;?>
        <?php if($total_length):?>
        <li><a href="<?php echo $this->url . $total_length;?>">尾页</a></li>
        <?php endif;?>
    </ul>
</nav>