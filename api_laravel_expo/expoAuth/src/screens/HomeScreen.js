import { useContext } from 'react';
import { Button, StyleSheet, Text, View } from 'react-native';
import { AuthContext } from '../context/AuthContext';

export default function HomeScreen() {
  const { userInfo, logout } = useContext(AuthContext)

  return (
    <View style={styles.container}>
      <Text style={styles.welcome}>Welcome {userInfo.user.name}</Text>
      <Button
        title='Logout'
        color='red'
        onPress={logout}
      />
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    alignItems: 'center',
    justifyContent: 'center',
  },
  welcome: {
    fontSize: 18,
    marginBottom: 8,
  },
});