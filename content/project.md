---
title: Projects
#author: Yihui Xie
---
<style>body {text-align: justify</style>

# WORK IN PROGRESS 

# Projects (November 2023)
Here there is a (non-exhaustive) list of my past and current projects



## Animal behaviour

In this project I was mostly interested in the use of mixture model to understand animal behaviour.  What initially began as an exploration of a poly-cylindrical distribution I developed, took an unexpectedly fascinating turn as I delved into the world of understanding animal behavior.
In my initial paper, we introduced a new formulation for the standard Logistic Gaussian process. What sets our approach apart from others is its invariance under the choice of the reference element, a feature not commonly found among competitors. You can read the details of this formulation [here](https://projecteuclid.org/journals/annals-of-applied-statistics/volume-13/issue-4/New-formulation-of-the-logistic-Gaussian-process-to-analyze-trajectory/10.1214/19-AOAS1289.full). 

Furthermore, I propose a novel distribution designed to model movement that exhibits both directional persistence and attraction to a specific point in space. The details of this  distribution can be found
[here](https://projecteuclid.org/journals/annals-of-applied-statistics/volume-16/issue-3/Modeling-animal-movement-with-directional-persistence-and-attractive-points/10.1214/21-AOAS1584.short). 

And I present a new approach to modeling the movement of multiple animals, assuming they can share some movement features. Check out the full details ([link](https://academic.oup.com/jrsssc/article/71/4/932/7072964))



## Models for Size and Shape data

This is a fairly new project for me. 
With these type of data you are interested in on extracting information regarding the shape and size of the dataset. There are very few papers on the topic, particularly in the realm of Bayesian inference and modeling in general.  I have co-authored a paper ([here](https://www.sciencedirect.com/science/article/pii/S0167715223001529)) that introduces a linear regression extension to a published frequentist approach.
The Julia package BayesSizeAndShape, that implement this model can be found  [here](https://github.com/GianlucaMastrantonio/BayesSizeAndShape.jl).

## Circular and Cylindrical Random Variables

Circular data was the topic of my Ph.D.
My primary focus revolved around extending the Gaussian process to accommodate circular response variables. I introduced a new process based on the Skew-Normal distribution, along with extensions of the Wrapped GP and Projected GP. You can find more details  [here](https://link.springer.com/article/10.1007/s00477-015-1163-9) or [here](https://link.springer.com/article/10.1007/s11749-015-0458-y). An R package that implement some of these model is [CircSpaceTime](https://github.com/GianlucaMastrantonio/CircSpaceTime): the details are in the [article](https://doi.org/10.1080/00949655.2020.1725008)

I proposed a new distribution for poly-cylindrical data, i.e., a distribution over a vector of linear and circular variables ([the paper](https://www.sciencedirect.com/science/article/pii/S0047259X17301069)), and I used cylindrical distributions in the context of Hidden Markov Models, for example  [link](https://link.springer.com/article/10.1007/s00477-015-1163-9) and [link](https://link.springer.com/article/10.1007/s11749-015-0458-y). 

## Genetic Data

I became involved in genetic data through my colleaguee [Enrico Bibbona](https://www.polito.it/en/staff?p=enrico.bibbona). 
Currently, we are working on the use of mixture models for the grouping of kinetic rates that govern mRNA dynamics, as well as the analysis of RNA velocity. 

<!--

## Environmental Statistics

## Statistic in Medicine

## Bayesian non-parametric

## Topic Modelling

## Spatial Extremes

## Acustic Data

## Climatic Modelling

-->

# Selected Papers (November 2023)
- Di Noia, Antonio; Mastrantonio, Gianluca; Jona Lasinio, Giovanna (2023). Bayesian size-and-shape regression modelling. In: Statistics & Probability Letters, vol. 204. 
- Donatelli, Aurora; Mastrantonio, Gianluca; Ciucci, Paolo (2022). Circadian activity of small brown bear populations living in human-dominated landscapes. In: SCIENTIFIC REPORTS, vol. 12. ISSN 2045-2322
- Mastrantonio, Gianluca  (2022)
Modeling animal movement with directional persistence and attractive points. In: THE ANNALS OF APPLIED STATISTICS, vol. 16. ISSN 1932-6157
- Mastrantonio, Gianluca (2022)
The modelling of movement of multiple animals that share behavioural features. In: JOURNAL OF THE ROYAL STATISTICAL SOCIETY SERIES C-APPLIED STATISTICS. 
-  Mastrantonio, Gianluca; Jona Lasinio, Giovanna; Pollice, Alessio; Teodonio, Lorenzo  (2021)
A Dirichlet process model for change‐point detection with multivariate bioclimatic data. In: ENVIRONMETRICS. ISSN 1180-4009

- Jona Lasinio G., Santoro M, Mastrantonio  G. (2020). CircSpaceTime: an R package for spatial and spatio-temporal modelling of circular data. Journal of Statistical Computation and Simulation, vol. 90, pp. 1315-1345

- Mastrantonio, Gianluca; Grazian, Clara; Mancinelli, Sara; Bibbona, Enrico (2019)
New formulation of the logistic-Gaussian process to analyze trajectory tracking data. In: THE ANNALS OF APPLIED STATISTICS, vol.  13, pp. 2483-2508. ISSN 1932-6157

- Saint-Hilary G., Barboux V., Pannaux M., Gasparini M., Robert V., Mastrantonio G. (2019). Predictive probability of success using surrogate endpoints. Statistics in Medicine, vol. 38, pp. 1753-1774

- Mastrantonio G., Jona Lasinio G.,  Pollice A.,  Capotorti G.,  Teodonio L.,  Genova G.,  Blasi C. (2019). A hierarchical multivariate spatio-temporal model for clustered climate data with annual cycles. Annals of Applied Statistics,  vol. 13, pp. 797-823, DOI: 10.1214/18-AOAS1212

- Maurella C.,  Mastrantonio G.,   Bertolini S.,  Crescio I.,  Ingravalle F.,  ,  Adkin A. ,   Simons R., De Nardi M., Estrada Pena A.,  Horigana V.,       Ru G.   (2019). Social network analysis and risk assessment: An example of introducing an exotic animal disease in Italy. Microbial Risk Analysis, DOI:  10.1016/j.mran.2019.04.001


- Mastrantonio G., Jona Lasinio G., Maruotti A., Calise G. (2019). Invariance properties and statistical inference for circular data. Statistica Sinica, vol. 29, pp. 67-80

- Mastrantonio G. (2018). The joint projected normal and skew-normal: A distribution
for poly-cylindrical data. Journal of Multivariate Analysis, vol. 165, pp. 14-26

- Mastrantonio G. ,  Pollice A.,  Fedele F. (2018). Distributions-oriented wind forecast verification by a hidden Markov model for multivariate circular-linear data. Stochastic environmental research and risk assessment, vol. 32, pp. 169-181


- Casoli E. ,  Nicoletti L.,  Mastrantonio G.,  Jona-Lasinio G.,  Belluscio A.,  Ardizzone G.D. (2017). Scuba diving damage on coralligenous builders: Bryozoan species as an indicator of stress. Ecological Indicators, vol. 74, pp. 441-450


- Tosoni E., Boitani L., Mastrantonio G., Latini R.,  Ciucci P. (2017). Counts of unique females with cubs in the Apennine brown bear population, 2006-2014. Ursus, vol. 28, pp. 1-14

- Mastrantonio G., Gelfand A., Jona-Lasinio G. (2016). The wrapped skew Gaussian process for analyzing spatio-temporal data. Stochastic Environmental Research and Risk Assessment, vol. 30, pp. 2231-2242

- Mastrantonio G.,  Jona-Lasinio G., Gelfand A. (2016). Spatio-temporal circular models with non-separable covariance structure. TEST, vol. 25, pp. 331-350

- Maruotti A.,  Punzo A.,  Mastrantonio G.,   Lagona F. (2016). A time-dependent extension of the projected normal regression model for longitudinal circular data based on a hidden Markov heterogeneity structure. Stochastic Environmental Research and Risk Assessment, vol. 30, pp. 1725-1740


- Mastrantonio G.,  Maruotti A.,  Jona Lasinio G. (2015). Bayesian hidden Markov modelling using circular-linear general projected normal distribution. Environmetrics, vol. 26, pp. 145-158


- Jona Lasinio G.,  Mastrantonio G.,  Pollice A. (2013). Discussing the ``big n problem''. Statistical Methods and Applications, vol. 22, pp. 97-112



