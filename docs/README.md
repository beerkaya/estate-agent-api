## API Documentation

This documentation is prepared from [Berk KAYA](https://github.com/beerkaya).

### Requirements

---

#### [Redoc CLI](https://redoc.ly/docs/redoc/quickstart/cli/)

```bash
yarn global add redoc-cli
```

#### [Yamlinc](https://github.com/javanile/yamlinc)

```bash
yarn global add yamlinc
```

### Development

Run the following command:

```bash
cd docs && yamlinc --watch redoc-cli serve swagger.yml -t template.hbs --watch --options.nativeScrollbars=true
```

Then visit the:

```bash
http://localhost:8080
```

### Build

Run the following command:

```bash
cd docs && yamlinc --exec "redoc-cli bundle -o public/index.html" swagger.yml
```
