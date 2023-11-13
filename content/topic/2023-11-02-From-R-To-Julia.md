---
title: "From R to Julia"
date: 2023-11-02
tags: ["Julia", "R", "Programming", "Statistics"]
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

The codes I will use can be found also [here](https://github.com/GianlucaMastrantonio/FromRtoJulia). I'm using Julia version 1.8.2.


# Tutorial
## The model

I just want to emphasize right from the start that I'm not an expert, and I'm pretty sure that there are better and different ways to do some of the things I'm about to explain. I might even have some concepts completely misunderstood (there are still parts of Julia that I use without fully grasping). We can now start with the tutorial. 

My idea is to try to implement a simple Bayesian regression model. I will assume that the data \\(\mathbf{y}=(y_1, \dots , y_n)\\) come from the model
$$
\mathbf{y} = \mathbf{X}\boldsymbol{\beta}+ \boldsymbol{\epsilon}
$$
where \\(\boldsymbol{\epsilon} \sim N(\mathbf{0}, \sigma^2\mathbf{I})\\), \\(\mathbf{X}\\) is a \\(n \times d\\) matrix of coefficients and  \\(\boldsymbol{\beta}\\) is a d-dimensional vector of regressors. The priors are
$$
\boldsymbol{\beta} \sim N(\mathbf{M}, \mathbf{V}) \ \ \ \ \ \ \ \ \ \ \ \sigma^2  \sim IG(a,b)
$$
Given these priors, the full conditionals of \\(\boldsymbol{\beta}\\) and \\(\sigma^2\\) are available in closed form and they are
$$
\boldsymbol{\beta} | \sigma^2, \mathbf{y} \sim N(\mathbf{M}_p, \mathbf{V}_p)
$$
with 
$$
\mathbf{V}_p = \left(\frac{n}{\sigma^2} + \mathbf{V}^{-1}\right)^{-1} \ \ \ \ \  \ \mathbf{M}_p = \mathbf{V}_p \left(\frac{\mathbf{X}^T\mathbf{X}}{\sigma^2} + \mathbf{N}\mathbf{V}^{-1}\right)^{-1}
$$
and
$$
 \sigma^2| \boldsymbol{\beta}, \mathbf{y} \sim IG \left(a+\frac{n}{2}, b+ \frac{\left( \mathbf{y}- \mathbf{X}\boldsymbol{\beta} \right)^T \left( \mathbf{y}- \mathbf{X}\boldsymbol{\beta} \right)}{2} \right)
$$
In the code I will assume  a prior  for  \\(\boldsymbol{\beta}\\) with independent components. 

## Julia 

When it comes to running Julia code, you have a choice of using the terminal or a more user-friendly option: **Visual Studio Code**. To make your experience with Julia smoother, I strongly recommend using Visual Studio Code. You can follow this [link](https://code.visualstudio.com/docs/languages/julia) for guidance on setting up Julia within Visual Studio Code.


The most challenging part of this tutorial lies in the initial configuration of Julia. It's essential to start by creating a new Julia package for your code. While creating a package may not always be your first instinct, it offers several advantages, especially when working with the **Revise** package, which I'll explain in detail later on. This approach will ultimately make your code development process more efficient.

### Package creation

I'm naming this package **RtoJulia**. We can use a function to generate the package structure. Let's open Julia, which can be done by typing "julia" in the terminal if you haven't set up a keyboard shortcut. Once you're inside Julia, you'll need to enter the following commands:
```julia
using Pkg
Pkg.generate("DirectoryOfThePackage_RtoJulia")
``` 
which will create the following structure:
```
|-- RtoJulia
|-- src
    ---RtoJulia.jl
```
It consists of a folder with the same name as the package. Inside this folder, you should create a **src** subdirectory, and within that, a **.jl** file named after the package itself. The structure of the **RtoJulia.jl** file should adhere to the following format:
```julia
module RtoJulia

end
``` 
Indeed, to make this package functional, you'll need files, functions, and dependencies. These files should be placed within the **src** folder, and you can organize them in subfolders as needed. For example, in this example, we will have a file named **model.jl** that contains the MCMC algorithm implemented in the function **mcmc()**. Additionally, there's a folder named **mcmc**, which contains the files **sample_beta.jl** and **sample_sigma2.jl**, each responsible for implementing the specific full conditional: this file and folder must be created, even if they are still empty. We don't need a file for each function, but I think that in this way the code is more organized. This results in the following package structure:
```
|-- RtoJulia
|-- src
    --- RtoJulia.jl
    --- model.jl
    |-- mcmc
        --- sample_beta.jl
        --- sample_sigma2.jl
```
In this package, you'll need to utilize several external packages, all of which are available on GitHub:
- **Distributions**: This package provides a wide range of probability distributions and related functions.
- **Random**: It allows you to work with random number generation and related functionalities.
- **LinearAlgebra**: This package offers essential linear algebra operations.
- **PDMat**s: PDMats is a package for positive definite matrices and operations.
- **StatsBase**: It provides fundamental statistics functionality for your package.

Then, the inside of the **RtoJulia.jl** is 
```julia
module RtoJulia

    using Distributions
    using Random
    using LinearAlgebra
    using PDMats
    using StatsBase

    include(joinpath("model.jl"))
    include(joinpath("mcmc/sample_beta.jl"))
    include(joinpath("mcmc/sample_sigma2.jl"))

    export mcmc

end
``` 
Where **include(joinpath())** is used to specify the location of the files, **using NAMEPACKAGE** is the Julia equivalent R **library(NAMEPACKAGE)**,  and export is employed to define which functions should be accessible outside the package. The **export mcmc** should be commented out until we write the function: you can comment part of the code with #, as in R.
To conclude the package setup, you'll need to create the **Manifest.toml** and **Project.toml** files using the appropriate function. While the exact details of what these files do are not completely clear to me, but they are essential for your package. 
Let's open Julia. Once you're inside Julia, you'll need to enter the following commands:
```julia
using Pkg
Pkg.activate("DirectoryOfThePackage_RtoJulia")
Pkg.add("Distributions")
Pkg.add("Random")
Pkg.add("LinearAlgebra")
Pkg.add("PDMats")
Pkg.add("StatsBase")
``` 
In Julia, **Pkg.add()** is equivalent to R **install.packages()**. Additionally, **Pkg.activate()** is employed to specify the folder in which you want to add these packages. Again, the precise mechanics of this process are not entirely clear to me.

The good news is that your package is now ready to be utilized.










### Installing the project packages 

For the first-time setup, you'll need to install the necessary packages for this project. If you create a new project, you'll need to repeat this installation step. While there is a way to install packages only once for all projects, based on my experience, it's often better to keep it project-dependent.

Remember, if you're not using the packages outside of the **RtoJulia.jl** package, you don't have to install them separately. However, in this example, we will need them. In addition to these packages, for the script, you'll also need:

- **Revise**: This package provides the ability to automatically update your code without restarting Julia.
- **RCall**: It facilitates interaction between Julia and R, allowing you to use R functions and libraries within your Julia script.
- **RtoJulia**: you own package.
just run the following code:
```julia
using Pkg
Pkg.activate("DirectoryOfTheProject")
Pkg.add("Distributions")
Pkg.add("Random")
Pkg.add("LinearAlgebra")
Pkg.add("PDMats")
Pkg.add("StatsBase")
Pkg.add("Revise")
Pkg.add("RCall")
Pkg.develop(path="DirectoryOfThePackage_RtoJulia")
``` 
You will see that **Pkg.activate** will create a new **Manifest.toml** and **Project.toml** files in the project directory. Again **Pkg.add()** will add the package while **Pkg.develop()** will tell Julia where your package is, and that it is in development (it will be helpful with **Revise**).


### The script

Now, you can create a script file wherever you like, and give it any name you prefer, as long as it has the **.jl** extension. In my case, I'll name it **sim_and_posterior_samples.jl**.
First, you'll need to activate the project and use the required packages. This can be done with the following code snippet. Please ensure that **dir_project** points to the directory where the **Manifest.toml** and **Project.toml** files of your project are located. Note that this step can be relatively slow, which is a known issue and disadvantage of Julia:


```julia
dir_project = "/Users/gianlucamastrantonio/Dropbox (Politecnico di Torino Staff)/lavori/gitrepo/FromRtoJulia"
using Pkg
Pkg.activate(dir_project)
using Revise
using Distributions, Random
using LinearAlgebra, PDMats, StatsBase
using RtoJulia
using RCall
```

    [32m[1m  Activating[22m[39m project at `~/Dropbox (Politecnico di Torino Staff)/lavori/gitrepo/FromRtoJulia`


I simulate \\(\mathbf{X}\\) with 

- n = 10000
- d = 2

and elements that are from a $N(0.0, \sqrt{2.0})


```julia

n::Int64 = 10000
d::Int64 = 2

xmat::Matrix{Float64} = Matrix{Float64}(undef, n,d)
for i = 1:d
    xmat[:,i] = rand(Normal(0.0,2.0), n)
end
```

From this simple code, we can already see some of the characteristics of Julia. 
The **for** syntax is
```julia
for index = sequence_of_values

end
```
As in R, **a:b** is used t0 indicate all the integers in [a,b]. 

IMPORTANT: arrays in Julia start at 1 and they are in column-major order.

To write efficient Julia code, it's crucial for Julia to know the data types of variables. This is achieved by using the **namevariable::type** syntax (but Julia will work even without).


In Julia, there are several essential types that you should be aware of, including **Float64**, **Int64**, **Matrix{Float64}**, **Matrix{Int64}**, and **Vector{Float64}**, **Vector{Int64}**: You can find detailed information about these types [here](https://docs.julialang.org/en/v1/manual/types/).

Similar to R, you can create a matrix in Julia using the following syntax: 
```julia
mat_test = Matrix(undef, n,d);
``` 
Here, the first argument specifies how to initialize the matrix, the second argument is the number of rows, and the third is the number of columns. However, in this case, Julia doesn't know what data type to expect for the matrix elements (e.g., Float or Int), and this can be determined using the **typeof()** function.




```julia
mat_test = Matrix(undef, n,d);
typeof(mat_test)
```


    Matrix{Any}[90m (alias for [39m[90mArray{Any, 2}[39m[90m)[39m


This reveals that the type of the matrix i **Any**. On the other hand, if we use:


```julia
typeof(xmat)
```


    Matrix{Float64}[90m (alias for [39m[90mArray{Float64, 2}[39m[90m)[39m


the matrix has a specific type.

Another important aspect of Julia is that Distributions are represented as objects. For instance, you can create an object that represents a normal distribution with a mean of 10 and a standard deviation of 0.1 using the following code:




```julia
norm_var = Normal(10.0,0.1^0.5)
typeof(norm_var)
```


    Normal{Float64}


We can then ask for the CDF evaluated at 12


```julia
cdf(norm_var,11.0)
```


    0.9992172988709987


for the log-pdf


```julia
logpdf(norm_var,11.0)
```


    -4.76764598670765


We can have a random sample


```julia
rand(norm_var)
```


    9.961229922673654


or 10


```julia
rand(norm_var,10)
```


    10-element Vector{Float64}:
     10.262302584791907
      9.788293824665672
     10.102299700754221
      9.756018705010291
      9.969009161753803
      9.627477777656386
     10.297995071518969
     10.333047770626505
      9.292472105875051
      9.749284355975002


When you have a matrix or even a vector, you can access its elements using the syntax **namevariable[indexrow, indexcolumn]**. For example, if you want to access all the elements in a particular column, you can use **:** to specify the entire range.


```julia
xmat[1:2,:]
```


    2×2 Matrix{Float64}:
     -0.129689  2.40786
     -0.295174  2.73471


If, like me, you've invested a lot of time in learning ggplot in R and still want to use it in your Julia workflow, there's a straightforward way to call R from Julia, thanks to the **RCall package**.

To start, you can copy objects into R using the **@rput** macro. For example, this command copies an object into R (if you don't want to display the results of a Julia operation, you can simply use a semicolon **;** at the end of it):


```julia
@rput xmat;
```

If you want to plot the first column of the **xmat** matrix using a standard R plot function, you can achieve this with the following code:


```julia
R"""
plot(xmat[,1])
"""
```


    RObject{NilSxp}
    NULL



Unfortunately, I'm unable to display R plots in this markdown environment, but I can assure you that if you execute the code in Julia, the R plot will indeed be generated and displayed.

Any command placed inside the triple-quoted block:
```julia
R"""
# codes
"""
```
will be executed in the R environment, allowing you to leverage R's plotting capabilities within your Julia workflow. 


If you create an object within R and you want to use it in Julia, you can achieve this by using the **@rget** macro. This allows you to seamlessly transfer objects from R to Julia, making it easy to work with R-generated data or results in your Julia code. 


```julia
R"""
an_r_object = 10
"""
@rget an_r_object;
an_r_object
```


    10.0


Be aware that only standard Julia and R structure can be moved between the two.

To define the first row of **xmat** as the intercept, you have two options: using a for loop or broadcasting. The first approach is straightforward and can be implemented as follows:
The first one is easy, and can be done as 


```julia
for i = 1:n
    xmat[i,1] = 1
end
```

on the other hand, this code is not valid


```julia
xmat[:,1] = 1
```


    ArgumentError: indexed assignment with a single value to possibly many locations is not supported; perhaps use broadcasting `.=` instead?


    


    Stacktrace:


     [1] setindex_shape_check(::Int64, ::Int64, ::Int64)


       @ Base ./indices.jl:261


     [2] _unsafe_setindex!(::IndexLinear, ::Matrix{Float64}, ::Int64, ::Base.Slice{Base.OneTo{Int64}}, ::Int64)


       @ Base ./multidimensional.jl:925


     [3] _setindex!


       @ ./multidimensional.jl:916 [inlined]


     [4] setindex!(::Matrix{Float64}, ::Int64, ::Function, ::Int64)


       @ Base ./abstractarray.jl:1399


     [5] top-level scope


       @ ~/Dropbox (Politecnico di Torino Staff)/lavori/gitrepo/gianlucamastrantonio.github.io/notebooks/2023-11-02-From-R-To-Julia.ipynb:1


When you're attempting to assign a scalar value to a vector, one solution is to use the following syntax:


```julia
xmat[:,1] .= 1;
```

#### It's important to exercise caution when making assignments in Julia

In Julia, there's the concept of immutable and mutable structures, which can be quite intricate for a tutorial. However, it's important to note that this introduces a significant issue if you're not careful.

To illustrate the problem, let's examine this code that creates two matrices: the first filled with zeros and the second with ones:


```julia
mat0 = zeros(Float64,3,2);
mat1 = ones(Float64,3,2);
```

Now, as we would typically do in R, we want to make the first matrix equal to the second one:



```julia
mat0 = mat1
println(mat0)
println(mat1)
```

    [1.0 1.0; 1.0 1.0; 1.0 1.0]
    [1.0 1.0; 1.0 1.0; 1.0 1.0]


It may seem like we've achieved the results we wanted, but here's where the issue arises. If we attempt to change the value of an element in **mat0**, something peculiar occurs with **mat1**.


```julia
mat0[1,1] = 10;
println(mat1)

```

    [10.0 1.0; 1.0 1.0; 1.0 1.0]


also the value of ****mat1** changed!! This happens because with 
```julia
mat0 = mat1
```
we're essentially making both variables point to the same object (I know, it's not precisely a pointer, but the behavior is akin to what people familiar with C++ might expect). Consequently, if we change an element in one, the other is affected as well. To avoid this, and ensure safe copying of elements from one matrix or vector to another, you can use squared brackets:


```julia
mat2 = ones(Float64,3,2)*2.0
mat2[:,:] = mat0[:,:];
```

or the function **deepcopy**:


```julia
mat3 = deepcopy(mat0)
```


    3×2 Matrix{Float64}:
     10.0  1.0
      1.0  1.0
      1.0  1.0


To check if two variables are the same, we can use **===**.


```julia
println(mat0===mat1)
println(mat0===mat2)
println(mat0===mat3)

```

    true
    false
    false


even thought the values inside the matrices are the same:


```julia
println(mat0==mat1)
println(mat0==mat2)
println(mat0==mat3)

```

    true
    true
    true


This behavior of matrices, vectors, and arrays, while it can be surprising at times, can actually be helpful when writing code. It allows for more efficient memory usage and can lead to significant performance gains in certain situations.

It's worth noting that this behavior doesn't occur with scalar variables. Whether this behavior arises depends on how objects are defined, whether they are mutable or immutable.


```julia
a1 = 2
a2 = 3
a1 = a2
a2 = 10
println(a1)
println(a2)
```

    3
    10


BACK TO THE CODE!

### Data simulation

Now we are ready to simulate the data


```julia
sigma2::Float64 = 3.0
regcoef::Vector{Float64} = rand(Normal(0.0,1.0),d);
ysim::Vector{Float64} = zeros(Float64,n)
xbeta = xmat*regcoef
for i = 1:n
    ysim[i] = rand(Normal(xbeta[i], sigma2^0.5))
end
```

In Julia, operations involving matrices are always defined as matrix operations by default. If you want to perform element-wise operations, you should use the operations with the dot, such as **.+** for element-wise addition or **./** for element-wise division.

## The functions

Now we need to write the actual MCMC algorithm. But, prior to do that, it is important to understand how functions works in Julia. The basic syntax is
```julia
function name_function(arguments1, argument2)
    
    ### compute something here

    ###
    return object_to_return

end
``````
indeed more than 2 arguments are possible. For example



```julia
function area_of_a_rectangle(lengtside1, lengtside2)

    println("First function")
    area = lengtside1*lengtside2
    return area
    
end
area_of_a_rectangle(1,2.1)
```

    First function



    2.1


As you can see, there's no need to specify the names of the arguments. Julia uses the argument positions.  Julia truly shines when it has full knowledge of the types of all variables and elements. Therefore, specifying types, as in the following approach, can lead to better performance:


```julia
function area_of_a_rectangle(lengtside1::Float64, lengtside2::Float64)::Float64

    println("Second function")
    area = lengtside1*lengtside2
    return area
    
end
area_of_a_rectangle(2.0,2.0)
```

    Second function



    4.0


The **::Float64** notation placed outside the parentheses is used to specify the type of the returned element. This provides Julia with crucial type information for optimal performance. 


It's worth noting that we now have two functions with the same name. Julia allows this because it can differentiate between them based on the types of the arguments. If both arguments are of type Float64, the second function will be used; otherwise, the first one will be invoked. Furthermore, you can even create a new function to compute the area of a rectangle if the arguments are of type Int, demonstrating the flexibility and versatility of Julia.


```julia
function area_of_a_rectangle(lengtside1::Int64, lengtside2::Int64)::Float64

    println("Third function")
    area = lengtside1*lengtside2
    return area
    
end
area_of_a_rectangle(2,2)
```

    Third function



    4.0



```julia
area_of_a_rectangle(2,2);
area_of_a_rectangle(2.0,2.0);
area_of_a_rectangle(2,2.0);
```

    Third function
    Second function
    First function


It's important to be aware that if one of your function's arguments is a mutable object, like a matrix or a vector, and you change one of its elements within the function, it will also be changed outside the function.
In Julia, when a function modifies the value of its argument, it's a common convention to add a **!** at the end of the function name to indicate this behavior. For example:


```julia
function area_of_a_rectangle!(lengtside1::Float64, lengtside2::Vector{Float64})::Float64

    println("Fourth function")
    area = lengtside1*lengtside2
    lengtside2[1] = 100
    lengtside1 = 10
    return area[1]
    
end
par1::Float64 = 2
par2::Vector{Float64} = [2]
area_of_a_rectangle!(par1, par2)
println(par1)
println(par2)
```

    Fourth function
    2.0
    [100.0]


As you can see in the example below, the element inside **par2** changes because it's a mutable object (a vector), while the scalar remains the same. This contrast in behavior is essential to understand when working with mutable and immutable objects in Julia. 



## The MCMC algorithm

Now, we can proceed to write the **mcmc** function within the package. In this process, the Revise package will prove highly beneficial. It automatically updates the package every time you make changes to a function within it, sparing you the need to manually intervene in the update process. This automation streamlines development and ensures that the latest code is always utilized. 

My mcmc function is
```julia
function mcmc(
    data::Vector{Float64},
    X::Matrix{Float64},
    mcmc::NamedTuple{(:iter, :thin, :burnin), Tuple{Int64, Int64, Int64}},
    priors::NamedTuple{(:regcoef, :sigma2), Tuple{Vector{Float64}, Vector{Float64}}},
    init::NamedTuple{(:regcoef, :sigma2), Tuple{Vector{Float64}, Vector{Float64}}}
)::Tuple{Matrix{Float64}, Matrix{Float64}}

    ncov = size(X,2)
    
    SampleToSave::Int64 = Int64(trunc(mcmc.iter-mcmc.burnin)/mcmc.thin)
    
    regcoefMCMC = Matrix{Float64}(undef,  ncov,1)
    regcoefMCMC[:] =  init.regcoef
    regcoefOUT = Matrix{Float64}(undef,  ncov, SampleToSave)
    
    sigma2MCMC = Matrix{Float64}(undef,  1,1)
    sigma2MCMC[:] =  init.sigma2
    sigma2OUT = Matrix{Float64}(undef,  1, SampleToSave)
    
    ### MCMC
    thinburnin = mcmc.burnin
      
    for iMCMC = 1:SampleToSave
        for jMCMC = 1:thinburnin
            ### sample beta
            sample_beta!(data,X,  priors.regcoef,regcoefMCMC, sigma2MCMC)
            ### sample sigma2
            sample_sigma2!(data,X,  priors.sigma2,regcoefMCMC, sigma2MCMC)
        end
        thinburnin = mcmc.thin
        
        regcoefOUT[:,iMCMC] .= regcoefMCMC
        sigma2OUT[:,iMCMC] .= sigma2MCMC
    end

    return regcoefOUT, sigma2OUT

end
```
The first argument of the function is the data, while the second is the matrix of covariates. The third argument is a **NamedTuple** of three integers. A **NamedTuple** is a collection of objects, somewhat akin to an R **list**. In this case, it indicates the total number of iterations, the thinning factor, and the burn-in value. You can create a **NamedTuple** as follows:
```julia
(iter = 10000, thin = 2, burnin = 2)
```
Then we have a **NamedTuple** with the parameters of the priors. Notice that I'm assuming 
$$
\mathbf{M} = (0,0)^T \ \ \ \ \mathbf{V} = 1000.0 \mathbf{I}, \ \ \ \  a= 1, \ \ \ \ b = 1
$$
The last argument is the initial values of the parameters.


The object **regcoefMCMC** and **sigma2MCMC** contains the values of the current iterations, while **regcoefOUT** and **sigma2OUT** are employed to store the posterior samples. The computations are carried out within the two for loops, where we also discard the burn-in samples. Within these loops, you'll find the two functions responsible for sampling from the full conditionals. This process is at the core of the MCMC algorithm for Bayesian inference.

The function **sample_beta!** is 
```julia
function sample_beta!(data::Vector{Float64},X::Matrix{Float64},  prior::Vector{Float64},regcoefMCMC::Matrix{Float64}, sigma2MCMC::Matrix{Float64})

    ncov = size(X,2)

    XtX::Symmetric{Float64, Matrix{Float64}} = Symmetric(transpose(X)*X)
    Xty::Matrix{Float64} = reshape(transpose(X)*data[:], (ncov,1))

    var_app = Symmetric(inv(XtX ./ sigma2MCMC[1] +  Diagonal( [ 1.0/  prior[2] for i = 1:ncov ] )))
    mean_app = var_app * (Xty ./ sigma2MCMC[1] .+ prior[1] /  prior[2])
    
    regcoefMCMC[:] = rand(MvNormal(mean_app[:],var_app))
    
end
``` 
while The function **sample_sigma2!** is 
```julia
function sample_sigma2!(data::Vector{Float64},X::Matrix{Float64},  prior::Vector{Float64},regcoefMCMC::Matrix{Float64}, sigma2MCMC::Matrix{Float64})

    ncov = size(X,2)

    mean = X*regcoefMCMC[:]
    
    para::Float64 = Float64(size(data[:],1)/2.0) +  prior[1]
    parb::Float64 = transpose((data[:]-mean))*(data[:]-mean)/2.0 +  prior[2]
    
    sigma2MCMC[:] .= 1/rand(Gamma(para,1.0/parb))
    
end
``` 
Notice that in both there is not **return** since they only change the values of the **Matrix** object **regcoefMCMC** and **sigma2MCMC**.

The last component of the **mcmc** function is
```julia
return regcoefOUT, sigma2OUT
```
The return type specification **Tuple** indicates that the function is expected to return two objects, which are then combined and placed inside a **Tuple**. 
TO be extra precise, at the end of the round parenthesis of the **mcmc** function I specified the output type as **::Tuple{Matrix{Float64}, Matrix{Float64}}**.

To conclude,  we can call this function in the script with


```julia
regcoefOUT, sigma2OUT = mcmc(
    ysim,
    xmat,
    (iter = 10000, thin = 2, burnin = 2),
    (regcoef=[0.0,1000.0], sigma2 = [1.0,1.0]),
    (regcoef = [0.0 for i = 1:size(xmat,2)], sigma2 = [1.0]),
);
```


The line
```julia
regcoefOUT, sigma2OUT = mcmc(...
```
serves to specify where to assign the two objects returned by the mcmc function. It's important to note that the return object and the outside object don't necessarily have to share the same name.

After obtaining the posterior samples in Julia using **regcoefOUT** and **sigma2OUT**, you can further analyze and validate your results by transferring these samples, along with the real values used to simulate the data, into R.




```julia
@rput regcoefOUT;
@rput sigma2OUT;
@rput regcoef;
@rput sigma2;

R"""
par(mfrow=c(3,3))
i =1
plot(regcoefOUT[i,], type="l")
abline(h =regcoefOUT[i], col=2, lwd=2)
acf(regcoefOUT[i,])
hist(regcoefOUT[i,])

i =2
plot(regcoefOUT[i,], type="l")
abline(h =regcoefOUT[i], col=2, lwd=2)
acf(regcoefOUT[i,])
hist(regcoefOUT[i,])

plot(sigma2OUT[1,], type="l")
abline(h =sigma2, col=2, lwd=2)
acf(sigma2OUT[1,])
hist(sigma2OUT[1,])

"""
```

Again, the plots are not showing here, but the will in your project.



# Conclusion

I sincerely hope that this tutorial has provided you with a glimpse into the world of Julia. Please be aware that this tutorial only scratches the surface of what Julia can achieve. We haven't delved into topics such as how to define a struct, how to use multiple dispatch effectively, how to utilize parametric types, and many more advanced concepts.

In any event, if you have questions or require further assistance on this or any other topic, please don't hesitate to reach out. I'm happy to help!

**The music I listen to while writing**
<iframe style="border-radius:12px" src="https://open.spotify.com/embed/album/6wtDAlsbBwBpTLxsIxqqUD?utm_source=generator&theme=0" width="100%" height="152" frameBorder="0" allowfullscreen="" allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture" loading="lazy"></iframe>
<iframe style="border-radius:12px" src="https://open.spotify.com/embed/album/7BYMJZFCYuGKi2jblMhyxg?utm_source=generator&theme=0" width="100%" height="152" frameBorder="0" allowfullscreen="" allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture" loading="lazy"></iframe>
<iframe style="border-radius:12px" src="https://open.spotify.com/embed/album/4mwrMLVKo940qLFXEIef4w?utm_source=generator&theme=0" width="100%" height="152" frameBorder="0" allowfullscreen="" allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture" loading="lazy"></iframe>
<iframe style="border-radius:12px" src="https://open.spotify.com/embed/album/5k42mshjg1CNN4nDIIM7Xx?utm_source=generator&theme=0" width="100%" height="152" frameBorder="0" allowfullscreen="" allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture" loading="lazy"></iframe>
