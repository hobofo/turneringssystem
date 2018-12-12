<div id="pageoptions">
        <ul>
                <li><a href="login.php">Log ud</a></li>
                
        </ul>
        <div>
                                <h3>Place for some configs</h3>
                                <p>Li Europan lingues es membres del sam familie. Lor separat existentie es un myth. Por scientie, musica, sport etc, litot Europa usa li sam vocabular. Li lingues differe solmen in li grammatica, li pronunciation e li plu commun vocabules. Omnicos directe al desirabilite de un nov lingua franca: On refusa continuar payar custosi traductores.</p>
        </div>
</div>


<header>
        <div id="logo">
                <a href="test.html">Logo Here</a>
        </div>
        <div id="header">
                <ul id="headernav">
                        <li><ul>
                                <li><a href="rangliste.php">Rangliste</a></li>
                                    <? if(8==9){ ?>
                                    <?php
                                        $turnering = hentturnering();
                                        if($turnering){
                                    ?>      <li><a href="turnering_start.php">Vis turnering</a>
                                            <span>I gang</span>
                                    <?php
                                        } else {
                                    ?>
                                    <li><a href="javascript:void(0);">Vis turnering</a>
                                    <? } ?>
                                    </li>
                                    <? } ?>

                        </ul></li>
                </ul>
               
        </div>
</header>

        <nav>
<ul id="nav">

        <li class="i_blocks_images"><a href="startnyturnering.php"><span>Start ny turnering</span></a></li>
        <li class="i_cog"><a href="turnering.php"><span>Turneringsindstillinger</span></a></li>
        
        <?php
                                        $turnering = hentturnering();
                                        if($turnering["puljer"] != ""){

                                       ?>
        <li class="i_cup"><a href="turnering_start.php"><span>Turnering</span></a></li>
        <li class="i_imac"><a href="turnering_live.php"><span>Turnering live</span></a></li>
                                  <?php } ?>
        <li class="i_users_2"><a href="brugere.php"><span>Brugere</span></a></li>
        <li class="i_cog_4"><a href="indstillinger.php"><span>Indstillinger</span></a></li>
        
</ul>
</nav>


