-- INTRODUCCION A SQL ANALITICO
--     ROLLUP y CUBE
SELECT deptno, puesto, sum(sal)
FROM empleados
GROUP BY ROLLUP(deptno, puesto);

SELECT deptno, puesto, sum(sal)
FROM empleados
GROUP BY CUBE(deptno, puesto);

-- Mostrar el monto de ventas por año por categoría, 
-- con los totales de venta por año ordenado por año
SELECT t.anio, p.categoria, sum(monto) 
FROM productos p, tiempo t, ventas v
WHERE p.prod_id = v.prod_id
AND  t.tiempo_id = v.tiempo_id
GROUP BY ROLLUP(t.anio, p.categoria)
ORDER by t.anio;

-- Anterior mas los totales por categoria ordenado por aÃ±o
SELECT t.anio, p.categoria, sum(monto) 
FROM productos p, tiempo t, ventas v
WHERE p.prod_id = v.prod_id
AND  t.tiempo_id = v.tiempo_id
GROUP BY CUBE(t.anio, p.categoria)
ORDER by t.anio;

-- Contar los productos vendidos por año y su promedio de venta, 
-- agrupados en categorías. Ordenados por año
SELECT t.anio, p.categoria, count(*), round(avg(monto),2)
FROM productos p, tiempo t, ventas v
WHERE p.prod_id = v.prod_id
AND  t.tiempo_id = v.tiempo_id
GROUP by t.anio, p.categoria
ORDER by t.anio;

-- Contar los productos vendidos por aÃ±o agrupados en categorÃ­as, con los 
-- totales por categoría y el promedio de venta. Ordenados por aÃ±o
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


-- FUNCIONES DE VENTANA
--     OVER
--Mostrar departamento con su cantidad de empleados
SELECT deptno,
       COUNT(*) "Empleados x Depto"
FROM empleados
GROUP BY deptno;

-- Mostrar nombre empleado, con su departamento y 
-- cantidad de empleados de su departamento 
SELECT nombre, deptno,
       COUNT(*) "Empleados x Depto"
FROM empleados
GROUP BY deptno;

SELECT nombre, deptno, 
       COUNT(*) over(partition by deptno ) "Empleados x Depto"
FROM empleados;

-- Mostrar nombre empleado, con su departamento y 
-- cantidad de empleados en su departamento y en toda la empresa 

SELECT nombre, deptno, 
       COUNT(*) over(partition by deptno ) "Empleados x Depto",
       COUNT(*) over() "Empleados Empresa"
FROM empleados;

-- Mostrar departamento, puesto, suma de salarios del departamento 
-- y suma de salarios por puesto 

SELECT deptno, puesto,
   SUM(sal) OVER (PARTITION BY deptno) AS tot_depto,
   SUM(sal) OVER (PARTITION BY puesto)  AS tot_puesto
FROM empleados
ORDER BY deptno, puesto;

SELECT distinct deptno, puesto,
   SUM(sal) OVER (PARTITION BY deptno) AS tot_depto,
   SUM(sal) OVER (PARTITION BY puesto)  AS tot_puesto
FROM empleados
ORDER BY deptno, puesto;

SELECT deptno, puesto, sum(sal) AS suma_deptoxpuesto,
   SUM(SUM(sal)) OVER (PARTITION BY deptno) AS total_depto,
   SUM(SUM(sal)) OVER (PARTITION BY puesto)  AS total_puesto
FROM empleados
GROUP BY deptno, puesto
ORDER BY deptno, puesto;

-- Mostrar el porcentaje que representa el sueldo de los empleado
-- en su departamento y en su puesto dentro de la compania
SELECT deptno, puesto, 
    COUNT(puesto) num_emp_dep, 
    SUM(sal) AS suma_deptoxpuesto, 
    SUM(SUM(sal)) OVER (PARTITION BY deptno) AS tot_depto, 
    ROUND(SUM(sal) * 100  /  SUM(SUM(sal)) OVER (PARTITION BY deptno)
           ,2)  AS "%PUESTO_DEPT",
    SUM(SUM(sal)) OVER (PARTITION BY puesto) AS total_puesto,
    SUM(COUNT(puesto)) over (partition by puesto) num_cia,
    ROUND(SUM(sal) * 100 / SUM(SUM(sal)) OVER (PARTITION BY puesto)
           ,2)  AS "%PUESTO_CIA"
FROM empleados
GROUP BY deptno, puesto
ORDER BY deptno, puesto;

SELECT deptno, puesto,
    COUNT(puesto) num_emp_dep, 
    ROUND(SUM(sal) * 100  /  SUM(SUM(sal)) OVER (PARTITION BY deptno)
           ,2)  AS "%PUESTO_DEPT",
    SUM(COUNT(puesto)) over (partition by puesto) num_cia,
    ROUND(SUM(sal) * 100 / SUM(SUM(sal)) OVER (PARTITION BY puesto)
           ,2)  AS "%PUESTO_CIA"
FROM empleados
GROUP BY deptno, puesto
ORDER BY deptno, puesto;

-- Ejemplo OVER con paises
-- Contar clientes por pais
select p.continente, count(*)
from paises p, clientes c
where p.pais_id = c.pais_id
group by p.continente
order by p.continente;

select distinct p.continente,
   count(*) over(partition by continente) 
from paises p, clientes c
where p.pais_id = c.pais_id
order by p.continente;

select p.continente, pais, count(*) "Total Pais",
       sum(count(*)) over(partition by continente) "Total Continente",
       sum(count(*)) over() "Total Mundial"
from paises p, clientes c
where p.pais_id = c.pais_id
group by p.continente,pais
order by p.continente,pais;

select p.continente, pais, count(*) "Total Pais",
   sum(count(*)) over(partition by continente) "Total Continente",
   sum(count(*)) over() "Total Mundial",
   round(count(*)*100/sum(count(*))over(partition by continente)
         ,2) "% Continental",
   round(count(*)*100/sum(count(*)) over(),2) "% Mundial"
from paises p, clientes c
where p.pais_id = c.pais_id
group by p.continente,pais
order by p.continente,pais;


-- FUNCIONES DE RANKING
--      ROWNUM
SELECT ROWNUM, producto, vendido
FROM ( SELECT p.nombre producto, SUM(v.cantidad) vendido
        FROM ventas v, productos p, tiempo t
        WHERE v.prod_id = p.prod_id
          AND v.tiempo_id = t.tiempo_id
          AND t.anio=2000
        GROUP BY p.nombre
        ORDER BY vendido )
WHERE ROWNUM <= 5;

--  FETCH
SELECT nombre, sal
FROM empleados
ORDER BY sal
FETCH FIRST 4 ROWS ONLY;

SELECT nombre, sal
FROM empleados
ORDER BY sal
FETCH FIRST 4 ROWS WITH TIES;

SELECT p.nombre producto, SUM(v.cantidad) vendido
FROM ventas v, productos p, tiempo t
WHERE v.prod_id = p.prod_id
  AND v.tiempo_id = t.tiempo_id
  AND t.anio=2000
GROUP BY p.nombre
ORDER BY vendido DESC
FETCH NEXT 5 ROWS ONLY;

SELECT p.nombre producto, SUM(v.cantidad) vendido
FROM ventas v, productos p, tiempo t
WHERE v.prod_id = p.prod_id
  AND v.tiempo_id = t.tiempo_id
  AND t.anio=2000
GROUP BY p.nombre
ORDER BY vendido
FETCH NEXT 5 ROWS ONLY;

-- ROW_NUMBER   RANK   DENSE_RANK
SELECT nombre, sal, empno,
    ROW_NUMBER() OVER (ORDER BY empno) row_n,
    RANK() OVER (ORDER BY empno) rank,
    DENSE_RANK() OVER (ORDER BY empno) dense
FROM empleados;

SELECT deptno, nombre, sal, empno,
    ROW_NUMBER() OVER (ORDER BY sal ) row_n,
    RANK() OVER (ORDER BY sal ) rank,
    DENSE_RANK() OVER (ORDER BY sal ) dense
FROM empleados;

SELECT deptno, nombre, sal, empno,
    ROW_NUMBER() OVER (ORDER BY sal DESC) row_n,
    RANK() OVER (ORDER BY sal DESC) rank,
    DENSE_RANK() OVER (ORDER BY sal DESC) dense
FROM empleados;

SELECT deptno, nombre, sal,
    ROW_NUMBER() OVER (PARTITION BY deptno ORDER BY sal DESC) row_n,
    RANK() OVER (PARTITION BY deptno ORDER BY sal DESC) rank,
    DENSE_RANK() OVER (PARTITION BY deptno ORDER BY sal DESC) dense
FROM empleados
ORDER BY deptno;

SELECT *
FROM  (SELECT nombre, sal, empno,
           DENSE_RANK() OVER (ORDER BY sal DESC) Top4
       FROM empleados)
WHERE Top4 < 5;

SELECT *
FROM  (SELECT nombre, categoria, precio_lista,
           RANK() OVER (PARTITION BY categoria ORDER BY precio_lista DESC) Top3
       FROM productos)
WHERE Top3 <= 3;

SELECT *
FROM (SELECT producto, categoria, vendido,
          RANK() OVER (PARTITION BY categoria ORDER BY vendido) Top2
      FROM (SELECT p.nombre producto, p.categoria, SUM(v.cantidad) vendido
            FROM ventas v, productos p, tiempo t
            WHERE v.prod_id = p.prod_id
               AND v.tiempo_id = t.tiempo_id
               AND t.anio=2000
            GROUP BY p.nombre, p.categoria )
        )
WHERE Top2 <= 2;

-- FUNCIONES DE POSICIÓN
--         RANK
-- Si llega un empleado con salario de $2,500 qué lugar ocupa entre los más altos
SELECT RANK(2500) WITHIN GROUP (ORDER BY sal DESC) AS Posicion
FROM empleados;

--Si llega un empleado con salario de $2,500
--¿Qué lugar ocupa por departamento con respecto a los empleados actuales?
SELECT deptno,
    RANK(2500) WITHIN GROUP (ORDER BY sal DESC) AS Posicion
FROM empleados
GROUP BY deptno;

SELECT deptno,
    RANK(2500) WITHIN GROUP (ORDER BY sal) AS Posicion
FROM empleados
GROUP BY deptno;

--     PERCENTIL
-- Ejemplo cuartiles
SELECT
    PERCENTILE_DISC(0.0) WITHIN GROUP (ORDER BY sal) AS minimo,
    PERCENTILE_DISC(0.25) WITHIN GROUP (ORDER BY sal) AS quartil_1,
    PERCENTILE_DISC(0.50) WITHIN GROUP (ORDER BY sal) AS quartil_2,
    PERCENTILE_DISC(0.75) WITHIN GROUP (ORDER BY sal) AS quartil_3,
    PERCENTILE_DISC(1) WITHIN GROUP (ORDER BY sal) AS maximo
FROM empleados;
-- Ejemplo MEdiana discreta y continua
SELECT deptno, round(avg(sal),2) promedio,
    PERCENTILE_DISC(0.5) WITHIN GROUP (ORDER BY sal) AS disc_as,
    PERCENTILE_DISC(0.5) WITHIN GROUP (ORDER BY sal DESC) AS disc_des,
    PERCENTILE_CONT(0.5) WITHIN GROUP (ORDER BY sal) AS cont_as,
    PERCENTILE_CONT(0.5) WITHIN GROUP (ORDER BY sal DESC) AS cont_des
FROM empleados
GROUP BY deptno;

SELECT deptno, nombre, sal, 
       PERCENTILE_CONT(0.5) WITHIN GROUP (ORDER BY sal DESC)
         OVER (PARTITION BY deptno) "Percentile_Cont"
  FROM empleados
  WHERE deptno in (10, 30);

SELECT nombre, sal as "SAL<=P20"
FROM empleados
WHERE sal <= (SELECT PERCENTILE_DISC(0.2) WITHIN GROUP (ORDER BY sal)
             FROM empleados) ;

-- LAG Y LEAD
--Mostrar departamento, nombre, salario y el nombre del siguiente 
--empleado que gana menos
SELECT deptno, nombre, sal, 
       LAG(nombre) OVER( ORDER BY sal ) "<=salario"
FROM empleados;

-- Mostrar nombre, salario y el nombre del siguiente empleado 
-- que gana menos junto con el salario y la diferencia entre estos
SELECT nombre, sal AS salario,
       LAG(nombre,1,' ') OVER(ORDER BY sal ) "empleado",
       LAG(sal,1,0) OVER(ORDER BY sal ) "<=salario",
       sal - LAG(sal) OVER(ORDER BY sal ) "dierencia"
FROM empleados;

-- Mostrar departamento, nombre, salario y el nombre del siguiente 
-- empleado que gana menos en el mismo departamento
SELECT deptno, nombre, sal AS salario, 
       LAG(nombre) OVER(PARTITION BY deptno ORDER BY sal ) "empleado",
       LAG(sal) OVER(PARTITION BY deptno ORDER BY sal ) "<=salario"
FROM empleados
ORDER BY deptno;

-- Mostrar departamento, nombre, salario y el nombre del siguiente 
-- empleado que gana 3 lugares menos en la empresa
SELECT deptno, nombre, sal, 
       LAG(nombre,3) OVER(ORDER BY sal ) "mucho menos salario"
FROM empleados
ORDER BY deptno;

-- Mostrar departamento, nombre, salario,  salario del siguiente empleado 
-- que gana menos y salario del  siguiente empleado que gana más para los 
-- departamentos 10 y 20
SELECT deptno, nombre, sal,
  LEAD(sal, 1, 0) 
     OVER (PARTITION BY deptno ORDER BY sal DESC NULLS LAST) sig_sal_bajo,
  LAG(sal, 1, 0) 
     OVER (PARTITION BY deptno ORDER BY sal DESC NULLS LAST) ant_sal_alto
FROM empleados
WHERE deptno IN (10, 20)
ORDER BY deptno, sal DESC;
