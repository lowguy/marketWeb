        </div>
    </div>
    <?php foreach($this->js as $js): ?>
        <script  src="<?php echo $this->cdn($js);?>"></script>
    <?php endforeach;?>
    </body>
</html>