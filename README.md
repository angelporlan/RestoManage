**DOCUMENTACIÓN DEL PROYECTO**

**DESCRIPCIÓN DEL PROYECTO**

Este proyecto consiste en el desarrollo de una aplicación web para la gestión y administración de pedidos de comida a domicilio. La aplicación está diseñada para facilitar tanto a los usuarios como a los restaurantes la gestión de pedidos, entregas y pagos. Los usuarios pueden explorar por el catálogo. Por su parte, los restaurantes pueden gestionar sus menús, recibir pedidos en tiempo real y coordinar las entregas.

**INSTALACIÓN**
El proyecto no esta alojado en el servidor, para probarlo de manera local:

1) Descarga el proyecto y instala las dependencias.
2) Installa XAMPP (si no lo tienes) y ejecuta mysql
3) Crea el archivo .ENV sí no esta y modifica la ruta de DATABASE_URL para que coincida con la tuya
4) Corre las migraciones e ejecuta symfony y vue. 

**JUSTIFICACIÓN**

La idea de desarrollar esta aplicación web surgió de la observación de la creciente demanda de servicios de entrega de comida a domicilio, especialmente en contextos urbanos y durante situaciones que limitan la movilidad, como la pandemia de COVID-19.

El proyecto tiene un alto potencial de éxito debido a la tendencia creciente hacia la digitalización de servicios y la conveniencia que ofrecen las plataformas de entrega a domicilio. Las posibilidades de venta y comercialización son amplias, ya que el mercado está en expansión y hay una demanda constante de soluciones eficientes para la gestión de pedidos y entregas de comida.

**DESARROLLO DEL PROYECTO**

**Fases del proyecto**

1. **Análisis**: Se realizó un análisis de mercado para entender las necesidades de los usuarios y los restaurantes. Se investigaron las características y funcionalidades de aplicaciones similares para identificar oportunidades de mejora.
1. **Investigación y recopilación de información**: Se recopilaron datos sobre tecnologías y herramientas disponibles para el desarrollo de la aplicación.
1. **Elección de herramientas de desarrollo**: Se eligieron las siguientes herramientas:

**Plataforma**: XAMPP

**Entorno de desarrollo**: Visual Studio Code **Base de Datos**: MySQL

4. **Elaboración de la aplicación**: Se desarrolló la aplicación utilizando los siguientes lenguajes de programación y frameworks:

**HTML**: Maquetación de la aplicación.

**CSS Responsive**: Adaptación a todas las resoluciones posibles. **PHP**: Comunicación con la base de datos.

**JavaScript**: Control de formularios, llamadas a endpoints y gestión de datos.

**Symfony** (junto con Api Platform): Desarrollo del backend.

**Vue** (junto con Vuex Store): Desarrollo del frontend y gestión del estado de la aplicación.

**Funcionamiento de la aplicación (Manual de Usuario)**

Al entrar a la aplicación, se accedera a la ruta ‘/’, que será la página principal,

![](https://dl.dropboxusercontent.com/scl/fi/f9l4c1vnln1qoinz8kj0p/Aspose.Words.e64a704a-e942-43ad-9d3f-0e973e6ecf2f.001.jpeg?rlkey=sylaqo3aum1kpxh2mo33cbk6c&st=dntjwpab&dl=0)

En esta se mostrará un breve texto a modo introductorio sobre la aplicación.

Más abajo se mostrarán las diferentes categorías de los diferentes platos que la plataforma ofrece. A continuación se mostrarán 3 platos destacados y un texto del objetivo de la aplicación.

![](https://dl.dropboxusercontent.com/scl/fi/yzm4hkncaoz9wzolz6v6x/Aspose.Words.e64a704a-e942-43ad-9d3f-0e973e6ecf2f.002.jpeg?rlkey=5xkvdwhuby45wwyx989fiesdm&st=zn7aru21&dl=0)

El usuario podrá añadir platos y llenar su cesta de productos, pero para continuar será necesario un inicio de sesión.

![](https://dl.dropboxusercontent.com/scl/fi/595thvrbe81fkywk5aetu/Aspose.Words.e64a704a-e942-43ad-9d3f-0e973e6ecf2f.003.jpeg?rlkey=qi7qvrj6ose6cnf1wowhv1c0m&st=exi7zsn2&dl=0)

El usuario podrá filtrar los platos por sus diferentes categorías. También podrá ver en detalle los diferentes alimentos que componen cada plato, sus valores nutricionales y su manual de uso.

![](https://dl.dropboxusercontent.com/scl/fi/5sjxct3um8eoxhyrmvjyz/Aspose.Words.e64a704a-e942-43ad-9d3f-0e973e6ecf2f.004.jpeg?rlkey=ybcjavo15ff3mn8tj3yzwscxg&st=aq5ng1x1&dl=0)

Cuando el usuario esté registrado, podrá completar el proceso de la compra.

![](https://dl.dropboxusercontent.com/scl/fi/hpjcqycu6uso3z0srqgv8/Aspose.Words.e64a704a-e942-43ad-9d3f-0e973e6ecf2f.005.jpeg?rlkey=s2b31sbld5qrst5awnq74k6io&st=y7sefplm&dl=0) El usuario podrá visualizar sus datos, el número total de pedidos y el número de dinero gastado en la aplicación. Además tendrá un listado de todos sus pedidos.

El usuario tendrá la opción de actualizar algunos de sus datos.

![](https://dl.dropboxusercontent.com/scl/fi/qrwi11rwugb0t3mvqyq41/Aspose.Words.e64a704a-e942-43ad-9d3f-0e973e6ecf2f.006.jpeg?rlkey=o48dgkaxnw4ho29qdaqmqhnav&st=0c5s3f2e&dl=0)

En la parte de descuentos, habrá dos objetivos principales, llegar a 100 euros en compras, y realizar 5 pedidos, cuando el usuario alcance la cifra, el usuario recibirá

un código de descuento para su próximo pedido. El código se puede compartir a otros usuarios

![](https://dl.dropboxusercontent.com/scl/fi/uz602slz8sd2gz0uevima/Aspose.Words.e64a704a-e942-43ad-9d3f-0e973e6ecf2f.007.jpeg?rlkey=1sapqbsusjeyrs2dqyk1vslnd&st=52eueczq&dl=0)

En la parte de administrador, aparecerá una opción en el perfil para visualizar todos los usuarios y todos los platos de la aplicación. Este podrá modificar los precios de los platos, crear nuevos platos, eliminarlos. Además de eliminar usuarios o ascender a administrador.

![](https://dl.dropboxusercontent.com/scl/fi/p0ks5c6sxkbolwufv1x3e/Aspose.Words.e64a704a-e942-43ad-9d3f-0e973e6ecf2f.008.jpeg?rlkey=dejsvau2s235yx7c754g6stms&st=c4cffyy1&dl=0)

**POSIBILIDADES DE AMPLIACIÓN**

Algunas mejoras que se podrían implementar en el futuro incluyen:

Mayor personalización en el perfil del usuario, preferencias de comida, pedidos semanales automatizados y más.

Mejora de la pasarela de pago para incluir más opciones de pago y aumentar la seguridad.

Añadir un chatbot para ayudar al usuario.

**BIBLIOGRAFÍA**

Sitio web de XAMPP: https://www.apachefriends.org/index.html Documentación de Visual Studio Code:[ https://code.visualstudio.com/docs ](https://code.visualstudio.com/docs)MySQL Official Documentation:[ https://dev.mysql.com/doc/](https://dev.mysql.com/doc/)

Symfony Official Documentation: https://symfony.com/doc/current/index.html Vue.js Documentation: https://vuejs.org/v2/guide/

Api Platform Documentation: https://api-platform.com/docs/

Vuex Documentation: https://vuex.vuejs.org/guide/
