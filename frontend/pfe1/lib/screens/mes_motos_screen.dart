import 'package:cached_network_image/cached_network_image.dart';
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../models/moto.dart';
import '../providers/moto_provider.dart';
import 'moto_parts_screen.dart';
import 'moto_details_screen.dart'; // Importer l'écran de détails

class MesMotosScreen extends StatefulWidget {
  @override
  _MesMotosScreenState createState() => _MesMotosScreenState();
}

class _MesMotosScreenState extends State<MesMotosScreen> {
  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addPostFrameCallback((_) {
      // Charge les motos du client au démarrage
      Provider.of<MotoProvider>(context, listen: false).loadClientMotos();
      Provider.of<MotoProvider>(context, listen: false).loadAvailableModels();
    });
  }

  void _navigateToPartsList(Moto moto) {
    Navigator.push(
      context,
      MaterialPageRoute(builder: (context) => MotoPartsScreen(moto: moto)),
    );
  }

  void _navigateToDetails(Moto moto) {
    Navigator.push(
      context,
      MaterialPageRoute(builder: (context) => MotoDetailsScreen(moto: moto)),
    );
  }

  @override
  Widget build(BuildContext context) {
    // Get screen dimensions
    final screenWidth = MediaQuery.of(context).size.width;
    final screenHeight = MediaQuery.of(context).size.height;

    return Scaffold(
      backgroundColor: Colors.black,
      body: Consumer<MotoProvider>(
        builder: (context, motoProvider, child) {
          if (motoProvider.isLoading) {
            return Center(child: CircularProgressIndicator(color: Colors.red));
          }

          if (motoProvider.error != null) {
            return Center(
              child: Column(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  Icon(Icons.error_outline, color: Colors.red, size: 48),
                  SizedBox(height: 16),
                  Text(
                    'Erreur: ${motoProvider.error}',
                    style: TextStyle(color: Colors.white),
                    textAlign: TextAlign.center,
                  ),
                  SizedBox(height: 16),
                  ElevatedButton(
                    style: ElevatedButton.styleFrom(
                      backgroundColor: Colors.red,
                    ),
                    onPressed: () => motoProvider.loadClientMotos(),
                    child: Text('Réessayer'),
                  ),
                ],
              ),
            );
          }

          return SingleChildScrollView(
            padding: EdgeInsets.all(screenWidth * 0.04),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  'Mes Motos',
                  style: TextStyle(
                    fontSize: screenWidth * 0.06,
                    fontWeight: FontWeight.bold,
                    color: Colors.white,
                  ),
                ),
                SizedBox(height: screenHeight * 0.01),
                Text(
                  'Gérez vos motos pour trouver rapidement des pièces compatibles',
                  style: TextStyle(
                    fontSize: screenWidth * 0.035,
                    color: Colors.grey[400],
                  ),
                ),
                SizedBox(height: screenHeight * 0.02),
                if (motoProvider.motos.isEmpty)
                  Center(
                    child: Column(
                      mainAxisAlignment: MainAxisAlignment.center,
                      children: [
                        Icon(
                          Icons.motorcycle,
                          size: screenWidth * 0.3,
                          color: Colors.grey,
                        ),
                        SizedBox(height: screenHeight * 0.02),
                        Text(
                          'Vous n\'avez pas encore ajouté de moto',
                          style: TextStyle(color: Colors.grey),
                        ),
                      ],
                    ),
                  )
                else
                  ListView.builder(
                    shrinkWrap: true,
                    physics: NeverScrollableScrollPhysics(),
                    itemCount: motoProvider.motos.length,
                    itemBuilder: (context, index) {
                      // Card for each motorcycle
                      final moto = motoProvider.motos[index];
                      final model = moto.model; // Peut être null
                      final marque = model?.marque ?? 'Inconnu';
                      final annee = model?.annee.toString() ?? 'Année inconnue';

                      return Card(
                        color: Colors.grey[900],
                        shape: RoundedRectangleBorder(
                          borderRadius: BorderRadius.circular(10),
                        ),
                        margin: EdgeInsets.symmetric(
                          vertical: screenHeight * 0.01,
                          horizontal: screenWidth * 0.02,
                        ),
                        child: Column(
                          children: [
                            // Image et infos
                            InkWell(
                              onTap: () => _navigateToDetails(moto),
                              child: Padding(
                                padding: EdgeInsets.all(screenWidth * 0.04),
                                child: Row(
                                  children: [
                                    // Image de la moto
                                    ClipRRect(
                                      borderRadius: BorderRadius.circular(8),
                                      child:
                                          moto.image != null &&
                                                  moto.image!.isNotEmpty
                                              ? CachedNetworkImage(
                                                imageUrl: moto.getImageUrl(),
                                                width: screenWidth * 0.25,
                                                height: screenWidth * 0.25,
                                                fit: BoxFit.cover,
                                                placeholder:
                                                    (context, url) => Container(
                                                      width: screenWidth * 0.25,
                                                      height:
                                                          screenWidth * 0.25,
                                                      color: Colors.grey[800],
                                                      child: Center(
                                                        child:
                                                            CircularProgressIndicator(
                                                              color: Colors.red,
                                                              strokeWidth: 2.0,
                                                            ),
                                                      ),
                                                    ),
                                                errorWidget: (
                                                  context,
                                                  url,
                                                  error,
                                                ) {
                                                  print(
                                                    'Erreur de chargement d\'image: $error pour URL: $url',
                                                  );
                                                  return Container(
                                                    width: screenWidth * 0.25,
                                                    height: screenWidth * 0.25,
                                                    color: Colors.grey[700],
                                                    child: Icon(
                                                      Icons.image_not_supported,
                                                      color: Colors.grey[500],
                                                      size: screenWidth * 0.1,
                                                    ),
                                                  );
                                                },
                                              )
                                              : Container(
                                                width: screenWidth * 0.25,
                                                height: screenWidth * 0.25,
                                                color: Colors.grey[700],
                                                child: Icon(
                                                  Icons.motorcycle,
                                                  color: Colors.grey[500],
                                                  size: screenWidth * 0.1,
                                                ),
                                              ),
                                    ),
                                    SizedBox(width: screenWidth * 0.04),
                                    // Informations de la moto
                                    Expanded(
                                      child: Column(
                                        crossAxisAlignment:
                                            CrossAxisAlignment.start,
                                        children: [
                                          Text(
                                            '$marque',
                                            style: TextStyle(
                                              fontSize: screenWidth * 0.05,
                                              fontWeight: FontWeight.bold,
                                              color: Colors.white,
                                            ),
                                            maxLines: 2,
                                            overflow: TextOverflow.ellipsis,
                                          ),
                                          SizedBox(
                                            height: screenHeight * 0.005,
                                          ),
                                          Text(
                                            'Année: $annee',
                                            style: TextStyle(
                                              fontSize: screenWidth * 0.04,
                                              color: Colors.grey[400],
                                            ),
                                            maxLines: 1,
                                            overflow: TextOverflow.ellipsis,
                                          ),
                                        ],
                                      ),
                                    ),
                                    // Menu d'options
                                    PopupMenuButton<String>(
                                      icon: Icon(
                                        Icons.more_vert,
                                        color: Colors.white,
                                      ),
                                      color: Colors.grey[800],
                                      onSelected: (value) {
                                        if (value == 'parts') {
                                          _navigateToPartsList(moto);
                                        } else if (value == 'details') {
                                          _navigateToDetails(moto);
                                        }
                                      },
                                      itemBuilder:
                                          (context) => [
                                            PopupMenuItem(
                                              value: 'details',
                                              child: Text(
                                                'Voir les détails',
                                                style: TextStyle(
                                                  color: Colors.white,
                                                ),
                                              ),
                                            ),
                                            PopupMenuItem(
                                              value: 'parts',
                                              child: Text(
                                                'Voir les pièces compatibles',
                                                style: TextStyle(
                                                  color: Colors.white,
                                                ),
                                              ),
                                            ),
                                          ],
                                    ),
                                  ],
                                ),
                              ),
                            ),
                            // Bouton d'achat de pièces séparé
                            Padding(
                              padding: EdgeInsets.fromLTRB(
                                screenWidth * 0.04,
                                0,
                                screenWidth * 0.04,
                                screenWidth * 0.04,
                              ),
                              child: SizedBox(
                                width: double.infinity,
                                child: ElevatedButton.icon(
                                  icon: Icon(
                                    Icons.shopping_cart,
                                    size: 18,
                                    color: Colors.white,
                                  ),
                                  label: Text(
                                    'Acheter des pièces',
                                    style: TextStyle(color: Colors.white),
                                  ),
                                  style: ElevatedButton.styleFrom(
                                    backgroundColor: Colors.red,
                                    padding: EdgeInsets.symmetric(vertical: 12),
                                  ),
                                  onPressed: () => _navigateToPartsList(moto),
                                ),
                              ),
                            ),
                          ],
                        ),
                      );
                    },
                  ),
              ],
            ),
          );
        },
      ),
    );
  }
}