package com.mygdx.game;

import com.badlogic.gdx.Gdx;
import com.badlogic.gdx.Preferences;
import com.badlogic.gdx.Screen;
import com.badlogic.gdx.graphics.Color;
import com.badlogic.gdx.graphics.Texture;
import com.badlogic.gdx.graphics.g2d.BitmapFont;
import com.badlogic.gdx.graphics.g2d.GlyphLayout;
import com.badlogic.gdx.utils.Align;
import com.badlogic.gdx.utils.ScreenUtils;

public class GameOverScreen implements Screen {
    private static final float BANNER_WIDTH = 350;
    private static final float BANNER_HEIGHT = 100;

    private final SpaceGame game;

    private final int score;
    private final int highscore;

    private final Texture gameOverBanner;

    private final BitmapFont scoreFont;

    GameOverScreen(SpaceGame game, int score) {
        this.game = game;
        this.score = score;

        Preferences prefs = Gdx.app.getPreferences("Galactic Defender");
        this.highscore = prefs.getInteger("highscore", 0);

        if (score > highscore) {
            prefs.putInteger("highscore", score);
            prefs.flush();
        }

        gameOverBanner = new Texture("fonts/game_over.png");
        scoreFont = new BitmapFont(Gdx.files.internal("fonts/score.fnt"));
    }

    @Override
    public void show() {

    }

    @Override
    public void render(float delta) {
        ScreenUtils.clear(0, 0, 0, 1);

        game.batch.begin();

        game.getBackground().updateAndRender(delta, game.batch);

        game.batch.draw(gameOverBanner, (float) SpaceGame.getWidth() / 2 - BANNER_WIDTH / 2, SpaceGame.getHeight() - BANNER_HEIGHT - 15, BANNER_WIDTH, BANNER_HEIGHT);

        GlyphLayout scoreLayout = new GlyphLayout(scoreFont, "Score: \n" + score, Color.WHITE, 0, Align.left, false);
        GlyphLayout highscoreLayout = new GlyphLayout(scoreFont, "Highscore: \n" + highscore, Color.WHITE, 0, Align.left, false);
        scoreFont.draw(game.batch, scoreLayout, (float) SpaceGame.getWidth() / 2 - scoreLayout.width / 2, SpaceGame.getHeight() - BANNER_HEIGHT - 15 * 2);
        scoreFont.draw(game.batch, highscoreLayout, (float) SpaceGame.getWidth() / 2 - highscoreLayout.width / 2, SpaceGame.getHeight() - BANNER_HEIGHT - scoreLayout.height - 15 * 5);

        float touchX = game.getCam().getInputInGameWorld().x;
        float touchY = SpaceGame.getHeight() - game.getCam().getInputInGameWorld().y;

        GlyphLayout tryAgainLayout = new GlyphLayout(scoreFont, "Try Again");
        GlyphLayout menuLayout = new GlyphLayout(scoreFont, "Main Menu");

        float tryAgainX = (float) SpaceGame.getWidth() / 2 - tryAgainLayout.width / 2;
        float tryAgainY = (float) SpaceGame.getHeight() / 2 - tryAgainLayout.height / 2;

        float menuX = (float) SpaceGame.getWidth() / 2 - menuLayout.width / 2;
        float menuY = (float) SpaceGame.getHeight() / 2 - menuLayout.height / 2 - tryAgainLayout.height - 15;

        //Controlla se il passaggio del mouse si trova su Try again button
        if (touchX >= tryAgainX && touchX < tryAgainX + tryAgainLayout.width && touchY >= tryAgainY - tryAgainLayout.height && touchY < tryAgainY) {
            tryAgainLayout.setText(scoreFont, "Try Again", Color.YELLOW, 0, Align.left, false);
        }

        //Controlla se il passaggio del mouse si trova su Menu button
        if (touchX >= menuX && touchX < menuX + menuLayout.width && touchY >= menuY - menuLayout.height && touchY < menuY) {
            menuLayout.setText(scoreFont, "Main Menu", Color.YELLOW, 0, Align.left, false);
        }

        //If Try again e Menu viene premuto
        if (Gdx.input.isTouched()) {
            //Try again
            if (touchX > tryAgainX && touchX < tryAgainX + tryAgainLayout.width && touchY > tryAgainY - tryAgainLayout.height && touchY < tryAgainY) {
                this.dispose();
                game.batch.end();
                game.setScreen(new GameScreen(game));
                return;
            }

            //Menu
            if (touchX > menuX && touchX < menuX + menuLayout.width && touchY > menuY - menuLayout.height && touchY < menuY) {
                this.dispose();
                game.batch.end();
                game.setScreen(new MenuScreen(game));
                return;
            }
        }

        //Disegnare pulsanti
        scoreFont.draw(game.batch, tryAgainLayout, tryAgainX, tryAgainY);
        scoreFont.draw(game.batch, menuLayout, menuX, menuY);

        game.batch.end();
    }

    @Override
    public void resize(int width, int height) {

    }

    @Override
    public void pause() {

    }

    @Override
    public void resume() {

    }

    @Override
    public void hide() {

    }

    @Override
    public void dispose() {

    }
}
