<noscript>
    <div id="brak_JS">Włączona obsługa JavaScript jest niezbędna do korzystania z tej strony!</div>
</noscript>
<div id="content">
<?= isset($this->bladRejestracja) ? '<div class="rej_blad_wys d_none"></div>' : '' ?>
    <div id="logo">
        <a href="<?=URL?>index"><img src="<?= URL ?>public/img/pokemon.png"/></a>
    </div>


    <div id="glowny_div">
        <div id="logowanie">
            <div id="zawartosc">

                <?php
                echo '<div class="informacja">';
                    echo 'Graczy online: '.$this->online;
                echo '</div>';
                if (isset($this->rej_login)) echo '<div class="informacja" style="color:green">Poprawnie zarejestrowano gracza o loginie: '.$this->rej_login.'</div>';
                ?>
            </div>
            <div id="przyciski">
                <div id="wysrodkuj">
                    <div id="zaloguj_sie_przycisk" class="button kursor button_submit">ZALOGUJ SIĘ</div>
                    <div id="zarejestruj_sie_przycisk" class="button kursor button_submit">ZAREJESTRUJ SIĘ</div>
                    <div class="clear"></div>
                </div>
            </div>
            <?php
            if (isset($this->wylogowano)) : ?>
            <div class="info info_wylogowano" id="wylogowano">
                <div id="wyloguj_zamknij">
                    <img src="<?=URL?>public/img/x.png" id="zamknij_wyloguj" class="zamknij_obrazek kursor" />
                </div>
                <?=$this->wylogowano?>
                <div class="rejestracja srodek">
                    <span class="kursor button_submit" id="zaloguj_ponownie">Zaloguj się ponownie</span></div>
            </div>

            <?php endif; ?>

            <div class="info" id="zaloguj">
                <div id="zaloguj_zamknij">
                    <img src="<?= URL ?>public/img/x.png" id="zamknij_zaloguj" class="zamknij_obrazek kursor"/>
                </div>
                <form action="<?= URL ?>zaloguj" method="post">
                    <input class="form_input input_200" type="text" placeholder="EMAIL LUB LOGIN" name="login" required>
                    <input class="form_input input_200" type="password" placeholder="HASŁO" name="haslo" required>
                    <?php //if(isset($this->lastpage)) echo $this->lastpage; ?>
                    <div class="rejestracja srodek">
                        Zapamiętaj: <input type="checkbox" name="autologin"/><br/>
                        <input type="submit" value="Zaloguj się" class="kursor button_submit2"/>
                    </div>
                    <div class="rejestracja srodek kursor" id="przypomnij">
                        Przypomnij hasło
                    </div>
                </form>
                <?php if (isset($this->blad)) echo $this->blad; ?>
            </div>
            <div class="info info_rejestracja" id="zarejestruj">
                <div id="naglowek_rejestracja">
                    <span id="tekst_naglowek">Rejestracja nowego trenera Pokemon</span>
                </div>
                <div id="zarejestruj_zamknij">
                    <img src="<?= URL ?>public/img/x.png" id="zamknij_zarejestruj" class="zamknij_obrazek kursor"/>
                </div>
                <form method="post" action="<?=URL?>index/rejestracja">
                    <div class="rej_blad"><?= $this->rej_blad ?? ''?></div>
                    <div class="rejestracja">
                        <div class="rejestracja_div_tekst">Imię trenera*</div>
                        <div class="rejestracja_div_input">
                            <input class="form_input input_100" id="login" placeholder="Imię trenera (login)*"
                                   type="text" name="login" value="<?= $this->fr_login ?? '' ?>"/><span class="okienko" id="okienko_login"></span></div>
                        <div class="clear"></div>
                        <div class="rej_blad"><?= $this->e_login ?? ''?></div>
                    </div>
                    <div class="rejestracja">
                        <div class="rejestracja_div_tekst">Email*</div>
                        <div class="rejestracja_div_input">
                            <input class="form_input input_100" id="email" placeholder="Email*" type="text" name="email"
                                   value="<?= $this->fr_email ?? '' ?>"><span class="okienko" id="okienko_email"></span></div>
                        <div class="clear"></div>
                        <div class="rej_blad"><?= $this->e_email ?? ''?></div>
                    </div>
                    <div class="rejestracja">
                        <div class="rejestracja_div_tekst">Hasło*</div>
                        <div class="rejestracja_div_input">
                            <input class="form_input input_100" placeholder="Hasło*" type="password" name="haslo">
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="rejestracja">
                        <div class="rejestracja_div_tekst">Powtórz hasło</div>
                        <div class="rejestracja_div_input">
                            <input class="form_input input_100" placeholder="Powtórz hasło" type="password"
                                   name="haslo2">
                        </div>
                        <div class="clear"></div>
                        <div class="rej_blad"><?= $this->e_haslo ?? ''?></div>
                    </div>

                    <div class="rejestracja">
                        <div class="rejestracja_div_tekst">Pierwszy pokemon</div>
                        <div class="rejestracja_div_input">
                            <select class="form_input" id="pok_rejestracja_form" name="pokemon">
                                <option id="pokemon1" value="1"<?= isset($this->fr_pok) && $this->fr_pok == 1 ? ' selected="yes"' : '' ?>>Bulbasaur
                                </option>
                                <option id="pokemon2" value="4"<?= isset($this->fr_pok) && $this->fr_pok == 4 ? ' selected="yes"' : '' ?>>Charmander
                                </option>
                                <option id="pokemon3" value="7"<?= isset($this->fr_pok) && $this->fr_pok == 7 ? ' selected="yes"' : '' ?>>Squirtle
                                </option>
                            </select></div>
                        <div class="clear"></div>
                        <div class="rej_blad"><?= $this->e_pok ?? ''?></div>
                    </div>
                    <div id="pok_rejestracja">
                    </div>

                    <div id="captcha">
                        <input type="checkbox" name="regulamin" id="reg"<?= isset($this->fr_regulamin) ? ' checked' : '' ?>/>
                        <label for="reg">Akceptuję regulamin</label>
                        <div class="rej_blad"><?= $this->e_regulamin ?? ''?></div>
                        <div class="g-recaptcha margin_top"
                             data-sitekey="6LcgbAsUAAAAAG6s_U5ACE43_5WXrdYXC_mshpl6"></div>
                        <div class="rej_blad"><?= $this->e_bot ?? ''?></div>
                    </div>
                    <div class="clear"></div>
                    <div class="rejestracja srodek">
                        <input type="submit" value="Zarejestruj trenera Pokemon"
                               class="kursor button_submit2 submit_rejestracja"/>
                    </div>
                </form>
                <div class="rejestracja">
                    <div class="rejestracja_informacja">*Login musi zawierać od 5 do 20 znaków (bez polskich znaków).
                    </div>
                    <div class="rejestracja_informacja">*Musisz podać poprawny adres Email w celu aktywacji konta. Twój
                        adres nie jest widoczny dla innych graczy.
                    </div>
                    <div class="rejestracja_informacja">*Hasło musi posiadać co najmniej 8 znaków. <a
                                href="http://www.bezpiecznypc.pl/zasady-tworzenia-hasel.php" target="_blank">Dowiedz się
                            jak stworzyć silne hasło.</a></div>
                </div>

            </div>

            <div class="info" id="przypomnij_div">
                <div id="przypomnij_zamknij">
                    <img src="<?= URL ?>public/img/x.png" id="zamknij_przypomnij" class="zamknij_obrazek kursor"/>
                </div>
                <form method="post" action="<?=URL?>index/przypomnij">
                    <input class="form_input input_200" type="text" placeholder="EMAIL LUB LOGIN" name="login" required>
                    <div class="rejestracja srodek">
                        <input type="submit" value="Przypomnij" class="kursor button_submit2"/>
                    </div>
                </form>

                <?php
                if (isset($this->blad) || isset($this->komunikat)) {
                    echo '<div class="przyp_blad">';
                    echo isset($this->blad) ? $this->blad : $this->komunikat;
                    echo '</div>';
                }
                ?>
            </div>
            <div class="informacja" id="nowe"></div>
            <!--<a href="zarejestruj-nowe-konto">Nie masz konta? Zarejestruj się!</a>
            <a href="przypomnij.php">Nie pamiętasz hasła?</a> -->
        </div>


        <div id="stopka">
            <div class="informacja_stopka">
                &copy; Nazwa Pokemon, postacie pokemonów i grafika są własnoscią firmy Nintendo<br/>
                &copy; 1995-2016 Nintendo/Creatures Inc./GAME FREAK inc. Pokemon and Pokemon character names are
                trademarks of Nintendo
            </div>
        </div>
    </div>
</div>
<div id="informacja">Zauważyliśmy, że używasz AdBlocka, prosimy o jego wyłączenie.</div>
</body>
</html>
