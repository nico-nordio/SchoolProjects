package com.mygdx.game;

import com.badlogic.gdx.Gdx;
import com.badlogic.gdx.InputAdapter;
import com.badlogic.gdx.Screen;
import com.badlogic.gdx.graphics.Texture;
import com.badlogic.gdx.utils.ScreenUtils;

public class MenuScreen implements Screen {

    private static final float EXIT_BUTTON_WIDTH = 200;
    private static final float EXIT_BUTTON_HEIGHT = 100;
    private static final float EXIT_BUTTON_Y = 90;

    private static final float PLAY_BUTTON_WIDTH = 200;
    private static final float PLAY_BUTTON_HEIGHT = 100;
    private static final float PLAY_BUTTON_Y = 205;

    private static final float TITLE_WIDTH = 500;
    private static final float TITLE_HEIGHT = 300;
    private static final float TITLE_Y1 = 450;
    private static final float TITLE_Y2 = 320;

    private final Texture playButtonActive;
     private final Texture playButtonInactive;

    private final Texture exitButtonActive;
    private final Texture exitButtonInactive;

    private final Texture title1;
    private final Texture title2;

    private final SpaceGame game;

    MenuScreen(final SpaceGame game) {
        this.game = game;
        playButtonActive = new Texture("buttons/play_button_active.png");
        playButtonInactive = new Texture("buttons/play_button_inactive.png");

        exitButtonActive = new Texture("buttons/exit_button_active.png");
        exitButtonInactive = new Texture("buttons/exit_button_inactive.png");

        title1 = new Texture("titles/title1.png");
        title2 = new Texture("titles/title2.png");

        final MenuScreen menuScreen = this;

        Gdx.input.setInputProcessor(new InputAdapter() {

            @Override
            public boolean touchDown(int screenX, int screenY, int pointer, int button) {
                //Exit button
                int x = (int) (SpaceGame.getWidth() / 2 - EXIT_BUTTON_WIDTH / 2);
                if (game.getCam().getInputInGameWorld().x < x + EXIT_BUTTON_WIDTH && game.getCam().getInputInGameWorld().x > x && SpaceGame.getHeight() - game.getCam().getInputInGameWorld().y < EXIT_BUTTON_Y + EXIT_BUTTON_HEIGHT && SpaceGame.getHeight() - game.getCam().getInputInGameWorld().y > EXIT_BUTTON_Y) {
                    menuScreen.dispose();
                    Gdx.app.exit();
                }

                //Play game button
                x = (int) (SpaceGame.getWidth() / 2 - PLAY_BUTTON_WIDTH / 2);
                if (game.getCam().getInputInGameWorld().x < x + PLAY_BUTTON_WIDTH && game.getCam().getInputInGameWorld().x > x && SpaceGame.getHeight() - game.getCam().getInputInGameWorld().y < PLAY_BUTTON_Y + PLAY_BUTTON_HEIGHT && SpaceGame.getHeight() - game.getCam().getInputInGameWorld().y > PLAY_BUTTON_Y) {

                    menuScreen.dispose();
                    game.setScreen(new GameScreen(game));

                }
                return super.touchUp(screenX, screenY, pointer, button);
            }
        });
    }

    @Override
    public void show() {

    }

    @Override
    public void render(float delta) {
        ScreenUtils.clear(0f, 0f, 0f, 1);

        game.batch.begin();

        game.getBackground().updateAndRender(delta, game.batch);

        int x = (int) (SpaceGame.getWidth() / 2 - EXIT_BUTTON_WIDTH / 2);
        if (game.getCam().getInputInGameWorld().x < x + EXIT_BUTTON_WIDTH && game.getCam().getInputInGameWorld().x > x && SpaceGame.getHeight() - game.getCam().getInputInGameWorld().y < EXIT_BUTTON_Y + EXIT_BUTTON_HEIGHT && SpaceGame.getHeight() - game.getCam().getInputInGameWorld().y > EXIT_BUTTON_Y) {
            game.batch.draw(exitButtonActive, x, EXIT_BUTTON_Y, EXIT_BUTTON_WIDTH, EXIT_BUTTON_HEIGHT);
        } else {
            game.batch.draw(exitButtonInactive, x, EXIT_BUTTON_Y, EXIT_BUTTON_WIDTH, EXIT_BUTTON_HEIGHT);
        }

        x = (int) (SpaceGame.getWidth() / 2 - PLAY_BUTTON_WIDTH / 2);
        if (game.getCam().getInputInGameWorld().x < x + PLAY_BUTTON_WIDTH && game.getCam().getInputInGameWorld().x > x && SpaceGame.getHeight() - game.getCam().getInputInGameWorld().y < PLAY_BUTTON_Y + PLAY_BUTTON_HEIGHT && SpaceGame.getHeight() - game.getCam().getInputInGameWorld().y > PLAY_BUTTON_Y) {
            game.batch.draw(playButtonActive, x, PLAY_BUTTON_Y, PLAY_BUTTON_WIDTH, PLAY_BUTTON_HEIGHT);
        } else {
            game.batch.draw(playButtonInactive, x, PLAY_BUTTON_Y, PLAY_BUTTON_WIDTH, PLAY_BUTTON_HEIGHT);
        }

        x = (int) (SpaceGame.getWidth() / 2 - TITLE_WIDTH / 2);
        game.batch.draw(title1, x, TITLE_Y1, TITLE_WIDTH, TITLE_HEIGHT);
        game.batch.draw(title2, x, TITLE_Y2, TITLE_WIDTH, TITLE_HEIGHT);

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
        Gdx.input.setInputProcessor(null);
    }
}
