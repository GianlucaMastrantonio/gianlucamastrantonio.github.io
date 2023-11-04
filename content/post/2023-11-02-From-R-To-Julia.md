---
title: "From R to Julia"
date: 2023-11-02
tags: ["Julia", "R", "Programming"]
draft: false
categories:
  - Tutorials
---
<style>body {text-align: justify}</style>


<!--!jupyter nbconvert notebooks/2023-11-02-From-R-To-Julia.ipynb --to markdown  --output 2023-11-02-From-R-To-Julia --output-dir "/Users/gianlucamastrantonio/Dropbox (Politecnico di Torino Staff)/lavori/gitrepo/gianlucamastrantonio.github.io/content/post" -->


I love programming and R is a fantastic language for statisticians. It's super flexible and great for data treatment. R has a lot of great qualities, but speed isn't one of them, which can be a problem if you're a Bayesians dealing with complex models. I used to call C++ inside R code to speed things up, so I could do all the initial data handling in R, and then let C++ do the real computation.  It worked well for me for quite some time, but let's be honest, debugging C++ code is a nightmare especially for someone like me who's not a computer scientist (and I'm sure to I'm not the only one with this problem), and dealing with compiler errors, missing libraries, and linking issues got old
really fast.  So, in early 2020, I had enough of C++. That's when I heard about Julia.  I printed out the whole Julia manual (yes, on paper) just a couple of days prior the first Covid lockdown in Italy.



I was stuck at home, with just Julia manual, and I thought, "Why not give it a shot?" I had a couple of ideas for modeling animal behavior, and I decided to try implementing them in Julia. Since then, I've ditched C++, and now, when I need to write code that's fast, reliable, and efficient, Julia is my go-to. Looking back at the code I wrote for those two projects, which you can find [here](https://github.com/GianlucaMastrantonio/multiple_animals_movement_model) and [here](https://github.com/GianlucaMastrantonio/STAP_HMM_model), even though they're not perfect, I'm still amazed at how, with just a bit of knowledge, I could write code way faster than I ever could in C++.  These ideas are published ([here](https://doi.org/10.1111/rssc.12561) and [here](https://projecteuclid.org/journals/annals-of-applied-statistics/volume-16/issue-3/Modeling-animal-movement-with-directional-persistence-and-attractive-points/10.1214/21-AOAS1584.short))



I think that for a person that knows R, switching to Julia is extremly simple.  It only takes a couple of days to get the hang of it and start implementing complex models. In fact, all my PhD students and some of my master's students use Julia. I've even given a few seminars on how to use Julia for speedy computations, and I thought it was a good idea to put it in a blog post.

The codes I will use can be found also [here](https://github.com/GianlucaMastrantonio/FromRtoJulia).


# The model

I just want to emphasize right from the start that I'm not an expert, and I'm pretty sure that there are better and different ways to do some of the things I'm about to explain. I might even have some concepts completely misunderstood (there are still parts of Julia that I use without fully grasping). We can now start with the tutorial. 

My idea is to try to implement a simple Bayesian regression model. I will assume that the data $\mathbf{y} = (y_1, \dots , y_n)$ come from the model
$$
\mathbf{y} = \mathbf{X}\boldsymbol{\beta}+ \boldsymbol{\epsilon}
$$
where \$\boldsymbol{\epsilon} \sim N(\mathbf{0}, \sigma^2\mathbf{I})\$, $\mathbf{X}$' is a $n \times p$ matrix of coefficients and $\boldsymbol{\beta}$ is a $p-$ dimensional vector of regressors. The priors are
$$
\boldsymbol{\beta} \sim N(\mathbf{M}, \mathbf{V}) \ \ \ \ \ \ \ \ \ \ \ \sigma^2  \sim IG(a,b)
$$
Given these priors, the full conditionals of $\boldsymbol{\beta}$ and $\sigma^2$ are available in closed form and they are
\begin{align}
\boldsymbol{\beta} | \sigma^2, \mathbf{y}& \sim N(\mathbf{M}_p, \mathbf{V}_p) \\
\boldsymbol{\beta} | \sigma^2, \mathbf{y}& \sim N(\mathbf{M}_p, \mathbf{V}_p) 
\end{align}



```julia

```

**The music I listen to while writing**
<iframe style="border-radius:12px" src="https://open.spotify.com/embed/album/6wtDAlsbBwBpTLxsIxqqUD?utm_source=generator&theme=0" width="100%" height="152" frameBorder="0" allowfullscreen="" allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture" loading="lazy"></iframe>


```julia


```
