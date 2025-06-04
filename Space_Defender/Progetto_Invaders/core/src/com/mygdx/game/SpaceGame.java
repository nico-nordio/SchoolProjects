package com.mygdx.game;

import com.badlogic.gdx.Game;
import com.badlogic.gdx.graphics.g2d.SpriteBatch;

public class SpaceGame extends Game {

    private static final int WIDTH = 480; // Larghezza dello schermo
    private static final int HEIGHT = 720; // Altezza dello schermo

    protected SpriteBatch batch;

    private Background background;

    private GameCamera cam;

    @Override
    public void create() {
        batch = new SpriteBatch();
        cam = new GameCamera(WIDTH, HEIGHT);

        this.background = new Background();
        this.setScreen(new MenuScreen(this));
    }

    @Override
    public void render() {
        batch.setProjectionMatrix(cam.combined());
        super.render();
    }

    @Override
    public void dispose() {
        batch.dispose();
    }

    @Override
    public void resize(int width, int height) {
        cam.update(width, height);
        super.resize(width, height);
    }

    public static int getHeight() {
        return HEIGHT;
    }

    public static int getWidth() {
        return WIDTH;
    }

    public GameCamera getCam() {
        return cam;
    }

    public Background getBackground() {
        return background;
    }
}
