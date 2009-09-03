/*

#############################################################################

# Header from the template:

#

# http://dev.mysql.com/sources/doxygen/mysql-5.1/udf__example_8c-source.html

#############################################################################

*/



my_bool distance_init(UDF_INIT *initid, UDF_ARGS *args, char *message);

longlong distance(UDF_INIT *initid, UDF_ARGS *args, char *is_null,

		  char *error);

  

longlong distance(UDF_INIT *initid __attribute__((unused)), UDF_ARGS *args,

		  char *is_null __attribute__((unused)),

		  char *error __attribute__((unused)))

{

  if (args->arg_count != 2)

    return -3;

  if (args->arg_type[0] !=  STRING_RESULT)

    return -1;

  if (args->arg_type[1] !=  STRING_RESULT)

    return -2;

  const char *wA = args->args[0];

  const char *wB = args->args[1];

  int m = args->lengths[0];

  int n = args->lengths[1];

  int cost =0;

  int i;

  int j;

  longlong D[(m+1)][(n+1)];

 

  for (i = 0; i <= m; i++){

    D[i][0] = i;

  }

  for (i = 1; i <= n; i++){

    D[0][i] = i;

  }

 

  for (i = 1; i <= m; i++){

    for (j = 1; j <= n; j++){

      if (wA[i-1] == wB[j-1])

        cost = 0;

      else

        cost = 1;

 

      if ((D[i-1][j] + 1 <= D[i][j-1] + 1) && (D[i-1][j] + 1 <= D[i-1][j-1] + cost)){ // min(D[i-1][j]+1)

        D[i][j] = D[i-1][j] + 1;

      }

      else{

        if ((D[i][j-1] + 1 <= D[i-1][j] + 1) && (D[i][j-1] + 1 <= D[i-1][j-1] + cost))  { // min(D[i][j-1]+1

          D[i][j] = D[i][j-1] + 1;

        }

        else{

	  D[i][j] = D[i-1][j-1] + cost;                                                // min(D[i-1][j-1]+1

        }

      }

    }

  }

  return D[m][n];

}

my_bool distance_init(UDF_INIT *initid __attribute__((unused)),

		      UDF_ARGS *args __attribute__((unused)),

		      char *message __attribute__((unused)))

{

  return 0;

}
