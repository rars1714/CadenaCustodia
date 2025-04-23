# Entendimiento del negocio
# d. Leer los datos
# i. Crear 2 subconjuntos de datos, uno por problema. 

library(dplyr)
setwd("D:/Clases Universidad/ProyectoFinal_ML")
datos = read.csv("datosTF.csv", header = TRUE, as.is = FALSE)
str(datos)
attach(datos)

# Datos problema 1
datos_p1 <- datos |>
  select(P1, P3, EDAD, SEXO, ENT, DOMINIO, REGION, EST_DIS, UPM_DIS, ESTRATO, P4)

# Datos problema 2
datos_p2 <- datos |>
  select(P1, P3, EDAD, SEXO, ENT, DOMINIO, REGION, EST_DIS, UPM_DIS, ESTRATO, P7)

# ii. Eliminar los renglones con NA y de las personas que no saben o no respondieron (código 9). 

# Eliminar NA's
datos_p1 <- na.omit(datos_p1)
datos_p2 <- na.omit(datos_p2)

# Seleccionar renglones donde NO HAY codigo 9

datos_p1 <- datos_p1 |>
  filter(P1 != 9, P3 != 9, P4 != 9)

datos_p2 <- datos_p2 |>
  filter(P1 != 9, P3 != 9, P7 != 9)

# iii. Sustituir en la variable dependiente, el valor numérico por su significado (código) 

datos_p1 <- datos_p1 |> 
  mutate(P4 = factor(P4, levels = c(1, 2), labels = c("Si", "No")))

datos_p2 <- datos_p2 |> 
  mutate(P7 = factor(P7, levels = c(1, 2, 3, 4 ,5), 
                     labels = c("Nunca", "Casi Nunca", "A veces", "Casi siempre", "Siempre")))

str(datos_p1)
str(datos_p2)

# iv. Reportar el número de renglones que quedó en cada uno. 

print(paste("Numero de renglones para problema 1: ", nrow(datos_p1)))
print(paste("Numero de renglones para problema 2: ", nrow(datos_p2)))

# Problema 1

# a. Número de datos faltantes por columna

colSums(is.na(datos_p1))

# b. Análisis univariado

library(moments)
for (columna in names(datos_p1)) {
  cat(columna,"\n")
  if(is.numeric(datos_p1[,columna])) {
    print(summary(datos_p1[,columna]))
    cat("Media: ",mean(datos_p1[,columna]),"\n")
    cat("Desviación estandar: ", sd(datos_p1[,columna]),"\n")
    cat("Rango:", diff(range(datos_p1[,columna])),"\n")
    cat("Curtosis: ", kurtosis(datos_p1[,columna]),"\n")
    cat("Asimetría: ", skewness(datos_p1[,columna]),"\n")
    cat("\n")
  } else {
    cat("Tabla de frecuencias: ")
    print(table(datos_p1[,columna]))
    cat("\n")
  }
}

# c. Análisis de correlación

datos_p1 |>
  select(-P4) |>
  cor()

# d. Realizar una comparación de la distribución de cada una de las variables con respecto a la variable de interés. 

library(ggplot2)

ggplot(datos_p1, aes(x = factor(P1), fill = factor(P4))) + 
  geom_bar(position = "stack") + 
  labs(title = "Distribución de P1 por niveles de P4",
       x = "P1", y = "Frecuencia",
       fill = "Grupo (P4)") +
  theme_minimal()


ggplot(datos_p1, aes(x = factor(P4), fill = factor(P3))) + 
  geom_bar(position = "stack") + 
  labs(title = "Distribución de P4 por niveles de P3",
       x = "P4", y = "Frecuencia",
       fill = "Grupo (P3)") +
  theme_minimal()


ggplot(datos_p1, aes(x = P4, y = EDAD, fill = factor(P4))) + 
  geom_boxplot() + 
  labs(title = "Distribución de edad por niveles de P4",
       x = "Grupo (P4)", y = "Edad") +
  theme_minimal()

ggplot(datos_p1, aes(x = factor(SEXO), fill = factor(P4))) + 
  geom_bar(position = "stack") + 
  labs(title = "Distribución de sexo por niveles de P4",
       x = "Sexo", y = "Frecuencia",
       fill = "Grupo (P4)") +
  theme_minimal()

ggplot(datos_p1, aes(x = P4, fill = factor(P4))) + 
  geom_bar() + 
  facet_wrap(~ factor(ENT), ncol = 4) +
  labs(title = "Distribución de P4 por ent",
       x = "Ent", y = "Frecuencia",
       fill = "P4") +
  theme_minimal()

ggplot(datos_p1, aes(x = factor(DOMINIO), fill = factor(P4))) + 
  geom_bar(position = "stack") + 
  labs(title = "Distribución de dominio por niveles de P4",
       x = "Dominio", y = "Frecuencia",
       fill = "Grupo (P4)") +
  theme_minimal()

ggplot(datos_p1, aes(x = factor(REGION), fill = factor(P4))) + 
  geom_bar(position = "stack") + 
  labs(title = "Distribución de region por niveles de P4",
       x = "Region", y = "Frecuencia",
       fill = "Grupo (P4)") +
  theme_minimal()

ggplot(datos_p1, aes(x = P4, y = EST_DIS, fill = factor(P4))) + 
  geom_boxplot() + 
  labs(title = "Distribución de est_dis por niveles de P4",
       x = "Grupo (P4)", y = "EST_DIS") +
  theme_minimal()

ggplot(datos_p1, aes(x = P4, y = UPM_DIS, fill = factor(P4))) + 
  geom_boxplot() + 
  labs(title = "Distribución de upm_dis por niveles de P4",
       x = "Grupo (P4)", y = "UPM_DIS") +
  theme_minimal()

ggplot(datos_p1, aes(x = factor(ESTRATO), fill = factor(P4))) + 
  geom_bar(position = "stack") + 
  labs(title = "Distribución de estrato por niveles de P4",
       x = "Estrato", y = "Frecuencia",
       fill = "Grupo (P4)") +
  theme_minimal()
