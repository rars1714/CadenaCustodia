drop table EMPLEADOS;
CREATE TABLE EMPLEADOS(
    "EMPNO" NUMBER(4), 
	"NOMBRE" VARCHAR2(10), 
	"PUESTO" VARCHAR2(9), 
	"GERENTE" NUMBER(4), 
	"CONTRATO" DATE, 
	"SAL" NUMBER(7,2), 
	"COM" NUMBER(7,2), 
	"DEPTNO" NUMBER(2)
); 
Insert into EMPLEADOS (EMPNO,NOMBRE,PUESTO,GERENTE,CONTRATO,SAL,COM,DEPTNO) 
values (7369,'SMITH','CLERK',7902,to_date('17/12/00','DD/MM/RR'),800,null,20);
Insert into EMPLEADOS (EMPNO,NOMBRE,PUESTO,GERENTE,CONTRATO,SAL,COM,DEPTNO) 
values (7499,'ALLEN','SALESMAN',7698,to_date('20/02/06','DD/MM/RR'),1600,300,30);
Insert into EMPLEADOS (EMPNO,NOMBRE,PUESTO,GERENTE,CONTRATO,SAL,COM,DEPTNO) 
values (7521,'WARD','SALESMAN',7698,to_date('22/02/91','DD/MM/RR'),1250,500,30);
Insert into EMPLEADOS (EMPNO,NOMBRE,PUESTO,GERENTE,CONTRATO,SAL,COM,DEPTNO) 
values (7566,'JONES','MANAGER',7839,to_date('02/04/01','DD/MM/RR'),2975,null,20);
Insert into EMPLEADOS (EMPNO,NOMBRE,PUESTO,GERENTE,CONTRATO,SAL,COM,DEPTNO) 
values (7654,'MARTIN','SALESMAN',7698,to_date('28/09/07','DD/MM/RR'),1250,1400,30);
Insert into EMPLEADOS (EMPNO,NOMBRE,PUESTO,GERENTE,CONTRATO,SAL,COM,DEPTNO) 
values (7698,'BLAKE','MANAGER',7839,to_date('01/05/89','DD/MM/RR'),2850,900,30);
Insert into EMPLEADOS (EMPNO,NOMBRE,PUESTO,GERENTE,CONTRATO,SAL,COM,DEPTNO) 
values (7782,'CLARK','MANAGER',7839,to_date('09/06/97','DD/MM/RR'),2450,200,10);
Insert into EMPLEADOS (EMPNO,NOMBRE,PUESTO,GERENTE,CONTRATO,SAL,COM,DEPTNO) 
values (7788,'SCOTT','ANALYST',7566,to_date('19/04/07','DD/MM/RR'),3000,null,20);
Insert into EMPLEADOS (EMPNO,NOMBRE,PUESTO,GERENTE,CONTRATO,SAL,COM,DEPTNO) 
values (7839,'KING','PRESIDENT',null,to_date('17/11/81','DD/MM/RR'),5000,null,10);
Insert into EMPLEADOS (EMPNO,NOMBRE,PUESTO,GERENTE,CONTRATO,SAL,COM,DEPTNO) 
values (7844,'TURNER','SALESMAN',7698,to_date('08/09/01','DD/MM/RR'),1500,0,30);
Insert into EMPLEADOS (EMPNO,NOMBRE,PUESTO,GERENTE,CONTRATO,SAL,COM,DEPTNO) 
values (7876,'ADAMS','CLERK',7788,to_date('23/05/07','DD/MM/RR'),1100,null,20);
Insert into EMPLEADOS (EMPNO,NOMBRE,PUESTO,GERENTE,CONTRATO,SAL,COM,DEPTNO) 
values (7900,'JAMES','CLERK',7698,to_date('03/12/05','DD/MM/RR'),950,null,30);
Insert into EMPLEADOS (EMPNO,NOMBRE,PUESTO,GERENTE,CONTRATO,SAL,COM,DEPTNO) 
values (7902,'FORD','ANALYST',7566,to_date('03/12/11','DD/MM/RR'),3000,null,20);
Insert into EMPLEADOS (EMPNO,NOMBRE,PUESTO,GERENTE,CONTRATO,SAL,COM,DEPTNO) 
values (7934,'MILLER','CLERK',7782,to_date('23/01/98','DD/MM/RR'),1300,null,10);
commit;

-- INTRODUCCION A SQL ANALITICO
--     ROLLUP y CUBE
SELECT deptno, puesto, sum(sal)
FROM empleados
GROUP BY ROLLUP(deptno, puesto);

SELECT deptno, puesto, sum(sal)
FROM empleados
GROUP BY CUBE(deptno, puesto);

-- Mostrar el monto de ventas por a�o por categor�a, 
-- con los totales de venta por a�o ordenado por a�o
SELECT t.anio, p.categoria, sum(monto) 
FROM productos p, tiempo t, ventas v
WHERE p.prod_id = v.prod_id
AND  t.tiempo_id = v.tiempo_id
GROUP BY ROLLUP(t.anio, p.categoria)
ORDER by t.anio;

-- Anterior mas los totales por categoria ordenado por año
SELECT t.anio, p.categoria, sum(monto) 
FROM productos p, tiempo t, ventas v
WHERE p.prod_id = v.prod_id
AND  t.tiempo_id = v.tiempo_id
GROUP BY CUBE(t.anio, p.categoria)
ORDER by t.anio;

-- Contar los productos vendidos por a�o y su promedio de venta, 
-- agrupados en categor�as. Ordenados por a�o
SELECT t.anio, p.categoria, count(*), round(avg(monto),2)
FROM productos p, tiempo t, ventas v
WHERE p.prod_id = v.prod_id
AND  t.tiempo_id = v.tiempo_id
GROUP by t.anio, p.categoria
ORDER by t.anio;

-- Contar los productos vendidos por año agrupados en categorías, con los 
-- totales por categor�a y el promedio de venta. Ordenados por año
SELECT t.anio, p.categoria, count(*), round(avg(monto),2) 
FROM productos p, tiempo t, ventas v
WHERE p.prod_id = v.prod_id
AND  t.tiempo_id = v.tiempo_id
GROUP BY ROLLUP(t.anio, p.categoria)
ORDER by t.anio;

--   SAMPLE y SEED
SELECT COUNT(*)
FROM paises;

SELECT COUNT(*)
FROM paises
SAMPLE(50);

SELECT COUNT(*)
FROM paises
SAMPLE(50) SEED(5);

SELECT round(avg(MONTO),2) "Promedio 5%"
FROM ventas
SAMPLE(5);

SELECT * FROM(
    SELECT CIUDAD,
        COUNT(*) AS MUJERES,
        RANK() OVER (ORDER BY COUNT(*) desc)as rango
        FROM CLIENTES
        WHERE GENERO = 'F'
    GROUP BY CIUDAD) where rango <=4;

SELECT PAIS, PRODUCTO, VENDIDO, TOP
FROM (
    SELECT p.PAIS,
           v.PRODUCTO,
           SUM(v.MONTO) AS VENDIDO,
           RANK() OVER (PARTITION BY p.PAIS ORDER BY SUM(v.MONTO) DESC) AS TOP
    FROM VENTAS v
    JOIN PAISES p ON v.PAIS_ID = p.PAIS_ID
    WHERE p.CONTINENTE = 'Europe'
    GROUP BY p.PAIS, v.PRODUCTO
)
WHERE TOP = 1;

SELECT INGRESO,
    MIN(CREDITO) as Minimo,
    ROUND(AVG(CREDITO),2) as Promedio,
    PERCENTILE_CONT(0.5) WITHIN GROUP(ORDER BY CREDITO) as MedianaCA,
    MAX(CREDITO) as Maximo
FROM CLIENTES
    WHERE INGRESO LIKE 'D:%'
    or INGRESO LIKE 'J:%'
    or INGRESO LIKE 'L:%'
    GROUP BY INGRESO
    
    SELECT GENERO, POSICION
FROM (
    SELECT GENERO,
           NACIMIENTO,
           RANK() OVER (PARTITION BY GENERO ORDER BY NACIMIENTO DESC) AS POSICION
    FROM CLIENTES
)
WHERE NACIMIENTO = 1989;
;

SELECT DISTINCT genero, posicion FROM (
    SELECT genero,
        nacimiento,
        RANK() OVER (PARTITION BY genero ORDER BY nacimiento DESC) AS posicion
    FROM clientes) 
    WHERE nacimiento = 1989;

SELECT pais, producto, vendido, top FROM (
    SELECT p.pais,
           pr.nombre AS producto,
           SUM(v.monto) AS vendido,
           RANK() OVER (PARTITION BY p.pais ORDER BY SUM(v.monto) DESC) as top
    FROM ventas v
        JOIN productos pr on v.prod_id = pr.prod_id
        JOIN clientes c on v.cliente_id = c.cliente_id
        JOIN paises p  on c.pais_id = p.pais_id
    WHERE p.continente = 'Europe'
    GROUP BY p.pais, pr.nombre)
    WHERE top = 1;



SELECT dia, mes, anio
FROM tiempo SAMPLE (0.5);

SELECT dia, mes, anio
FROM tiempo
SAMPLE(.5) SEED(101);

SELECT p.pais,c.ciudad,
       COUNT(*) as "Num clientes",
       ROUND(100.0 * COUNT(*) / SUM(COUNT(*)) OVER (), 2) as "% clientes"
FROM clientes c
JOIN paises p ON p.pais_id = c.pais_id
WHERE p.region = 'Australia'
GROUP BY p.pais, c.ciudad
ORDER BY p.pais;


SELECT genero, ingreso,
TRUNC(avg(credito)) AS Promedio, MIN(credito) As Minimo
FROM clientes
WHERE ingreso LIKE 'D:%'
or ingreso LIKE 'L:%'
GROUP BY ROLLUP(genero, ingreso);


SELECT p1.nombre,
       p1.precio_lista,
       NVL(p2.nombre, '***') AS siguiente,
       NVL(p2.precio_lista, 0) AS precio,
       NVL(p2.precio_lista - p1.precio_lista, 0) AS diferencia
FROM productos p1
LEFT JOIN productos p2
  ON p2.precio_lista = (
     SELECT MIN(p3.precio_lista)
     FROM productos p3
     WHERE p3.precio_lista > p1.precio_lista
)
ORDER BY p1.precio_lista;


select * from paises;
